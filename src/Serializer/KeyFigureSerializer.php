<?php

namespace App\Serializer;

use App\Dto\ArchiveInput;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class KeyFigureSerializer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $decorated;

    private $defaultFields = [
        'id' => 'id',
        'figure_id' => 'figure_id',
        'iso3' => 'iso3',
        'country' => 'country',
        'year' => 'year',
        'name' => 'name',
        'value' => 'value',
        'valueString' => 'valueString',
        'valueType' => 'valueType',
        'value_string' => 'value_string',
        'value_type' => 'value_type',
        'updated' => 'updated',
        'url' => 'url',
        'source' => 'source',
        'description' => 'description',
        'tags' => 'tags',
        'provider' => 'provider',
        'archived' => 'archived',
        'extra' => 'extra',
        'data' => 'data',
        'values' => 'values',
        'unit' => 'unit',
    ];

    public function __construct(NormalizerInterface $decorated)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, $format = null, array $context = []) : bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, $format = null, array $context = []) : array|string|int|float|bool|\ArrayObject|null
    {
        $data = $this->decorated->normalize($object, $format, $context);

        // Add textual values if needed.
        if (isset($data['valueString']) && $data['value'] == '0.00') {
            $data['value'] = $data['valueString'];
        }
        unset($data['valueString']);

        // Hide figureId.
        unset($data['figureId']);

        // Add extra fields.
        if (isset($data['extra'])) {
            if (is_array($data['extra'])) {
                $data += array_diff_key($data['extra'], $this->defaultFields);
            }
            unset($data['extra']);
        }

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = []) : bool {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function denormalize($data, string $type, string $format = null, array $context = []) : mixed {
        // We need an operation.
        if (!isset($context['operation'])) {
            return $this->decorated->denormalize($data, $type, $format, $context);
        }

        /** @var \ApiPlatform\Metadata\HttpOperation $operation */
        $operation = $context['operation'];
        $input = $operation->getInput();

        // Use default denormalizer for archive.
        if ($input && $input['class'] == ArchiveInput::class) {
          return $this->decorated->denormalize($data, $type, $format, $context);
        }

        // Get extra properties.
        $properties = $operation->getExtraProperties() ?? [];

        // Check if we need to do something.
        if (!isset($properties['expand']) || $properties['expand'] !== 'key_figures') {
          return $this->decorated->denormalize($data, $type, $format, $context);
        }

        $provider = $properties['provider'] ?? NULL;
        $method = strtoupper($operation->getMethod());

        // Multiple records.
        if (isset($data['data']) && $method == 'POST') {
            if (is_array($data['data'])) {
                foreach ($data['data'] as &$row) {
                    $row = $this->checkAndCleanData($row, $provider, $method);
                }
            }
        }
        else {
          $data = $this->checkAndCleanData($data, $provider, $method);
        }

        return $this->decorated->denormalize($data, $type, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer) {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }

    protected function buildId($item) : string {
        $id = implode('_', [
            strtolower($item['provider']),
            strtolower($item['iso3']),
            $item['year'],
            $this->buildFigureId($item['name']),
        ]);

        $id = preg_replace('/[^A-Za-z0-9\-_]/', '', $id);

        return $id;
    }

    protected function buildFigureId($name) : string {
      return strtolower(preg_replace('/[^A-Za-z0-9\-_]/', '-', $name));
    }

    protected function checkAndCleanData($data, $provider, $method) {
        // Force provider.
        if ($provider) {
          $data['provider'] = $provider;
        }

        // Type conversion.
        if (isset($data['year'])) {
          $data['year'] = (string) $data['year'];
        }
        if (isset($data['value'])) {
          $data['value'] = (string) $data['value'];

          // Check input type, map to string_value
          if (!is_numeric($data['value'])) {
            $data['valueString'] = $data['value'];
            if (!isset($data['valueType']) || empty($data['valueType'])) {
              $data['valueType'] = 'string';
            }
            $data['value'] = '0';
          }
          else {
            $data['valueString'] = NULL;
            if (!isset($data['valueType']) || empty($data['valueType'])) {
              $data['valueType'] = 'numeric';
            }
          }
        }

        // SKip for PATCH.
        if ($method != 'PATCH') {
          // Set Id if not set.
          if (!isset($data['id'])) {
              $data['id'] = $this->buildId($data);
          }

          // Force figure Id.
          if (!isset($data['figure_id']) || empty($data['figure_id'])) {
            $data['figure_id'] = $this->buildFigureId($data['name']);
          }
        }

        // Check for any extra keys.
        if (!empty(array_diff_key($data, $this->defaultFields))) {
            // Move them into extra.
            $data['extra'] = $data['extra'] ?? [];
            $data['extra'] += array_diff_key($data, $this->defaultFields);
            $data = array_diff_key($data, $data['extra']);
        }

        return $data;
    }
}

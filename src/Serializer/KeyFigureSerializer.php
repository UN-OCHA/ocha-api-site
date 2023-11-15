<?php

namespace App\Serializer;

use App\Dto\ArchiveInput;
use App\Dto\BatchCollection;
use App\Entity\KeyFigures;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;

final class KeyFigureSerializer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $decorated;

    private $defaultFields = [
        'id' => 'id',
        'figureId' => 'figureId',
        'figure_id' => 'figure_id',
        'externalId' => 'external_id',
        'external_id' => 'external_id',
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
            throw new InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
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
        if (!is_array($data)) {
            return $data;
        }

        // Add textual values if needed.
        if (isset($data['value_string']) && $data['value'] == '0.00') {
            $data['value'] = $data['value_string'];
        }
        unset($data['value_string']);

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
        if (!is_a($type, KeyFigures::class, true)) {
            return FALSE;
        }

        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            KeyFigures::class => true,
        ];
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
            if (!is_array($data['data'])) {
                // Fail hard.
                throw new InvalidArgumentException('The data property has to be an array for batch updates');
            }

            foreach ($data['data'] as &$row) {
                $row = $this->checkAndCleanData($row, $provider, $method);
            }
        }
        elseif (isset($data['data'])) {
            // Not allowed with other methods.
            throw new InvalidArgumentException('The data property can not be used');
        }
        else {
          $data = $this->checkAndCleanData($data, $provider, $method);
        }

        return $this->decorated->denormalize($data, $type, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer): void {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }

    protected function buildId($item) : string {
        $parts = [
            strtolower($item['provider']),
            strtolower($item['iso3']),
            $item['year'],
        ];

        if (isset($item['external_id']) && !empty(['external_id'])) {
            $parts[] = $this->buildFigureId($item['external_id']);
        }

        $parts[] = $this->buildFigureId($item['name']);

        $id = implode('_', $parts);
        $id = preg_replace('/[^A-Za-z0-9\-_]/', '', $id);

        return $id;
    }

    protected function buildFigureId($name) : string {
      return strtolower(preg_replace('/[^A-Za-z0-9\-_]/', '-', $name));
    }

    protected function checkAndCleanData($data, $provider, $method) {
        // Force provider.
        if ($provider && !empty($provider)) {
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
            $data['value_string'] = $data['value'];
            if (!isset($data['value_type']) || empty($data['value_type'])) {
              $data['value_type'] = 'string';
            }
            $data['value'] = '0';
          }
          else {
            $data['value_string'] = NULL;
            if (!isset($data['value_type']) || empty($data['value_type'])) {
              $data['value_type'] = 'numeric';
            }
          }
        }

        // Skip for PATCH.
        if ($method != 'PATCH') {
          // Set Id if not set.
          if (!isset($data['id'])) {
              $data['id'] = $this->buildId($data);
          }

          // Force figure Id.
          if (!isset($data['figure_id']) || empty($data['figure_id'])) {
            $data['figure_id'] = $this->buildFigureId($data['name']);
          }

          if (!isset($data['archived'])) {
            $data['archived'] = FALSE;
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

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

    public function supportsDenormalization($data, $type, $format = null, array $context = []) : bool
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function denormalize($data, string $type, string $format = null, array $context = []) : mixed
    {
      // We need an operation.
      if (!isset($context['operation'])) {
          return $this->decorated->denormalize($data, $type, $format, $context);
      }

      /** @var \ApiPlatform\Metadata\Operation $operation */
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

      // Multiple records.
      if (isset($data['data'])) {
          if (is_array($data['data'])) {
              foreach ($data['data'] as &$row) {
                  // Force provider.
                  if ($provider) {
                      $row['provider'] = $provider;
                  }

                  // Set Id if not set.
                  if (!isset($row['id'])) {
                      $row['id'] = implode('_', [
                          $row['provider'],
                          $row['iso3'],
                          $row['year'],
                          $row['name'],
                      ]);

                      $row['id'] = preg_replace('/[^A-Za-z0-9\-_]/', '', $row['id']);
                  }

                  // Force figure Id.
                  if (!isset($row['figure_id']) || empty($row['figure_id'])) {
                    $row['figure_id'] = strtolower(preg_replace('/[^A-Za-z0-9\-_]/', '-', $row['name']));
                  }

                  // Type conversion.
                  $row['year'] = (string) $row['year'];
                  $row['value'] = (string) $row['value'];

                  // Check input type, map to string_value
                  if (!is_numeric($row['value'])) {
                    $row['valueString'] = $row['value'];
                    if (!isset($row['valueType']) || empty($row['valueType'])) {
                      $row['valueType'] = 'string';
                    }
                    $row['value'] = '0';
                  }
                  else {
                    $row['valueString'] = NULL;
                    if (!isset($row['valueType']) || empty($row['valueType'])) {
                      $row['valueType'] = 'numeric';
                    }
                  }

                  // Check for any extra keys.
                  if (!empty(array_diff_key($row, $this->defaultFields))) {
                      // Move them into extra.
                      $row['extra'] = $row['extra'] ?? [];
                      $row['extra'] += array_diff_key($row, $this->defaultFields);
                      $row = array_diff_key($row, $row['extra']);
                  }
              }
          }
      }
      else {
          // Force provider.
          if ($provider) {
              $data['provider'] = $provider;
          }

          // Force figure Id.
          if (!isset($data['figure_id']) || empty($data['figure_id'])) {
            $data['figure_id'] = strtolower(preg_replace('/[^A-Za-z0-9\-_]/', '', $data['name']));
          }

          // Type conversion.
          $data['year'] = (string) $data['year'];
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

          // Check for any extra keys.
          if (!empty(array_diff_key($data, $this->defaultFields))) {
              // Move them into extra.
              $data['extra'] = $data['extra'] ?? [];
              $data['extra'] += array_diff_key($data, $this->defaultFields);
              $data = array_diff_key($data, $data['extra']);
          }
      }

      return $this->decorated->denormalize($data, $type, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}

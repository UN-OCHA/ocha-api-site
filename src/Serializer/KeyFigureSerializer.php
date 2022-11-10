<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class KeyFigureSerializer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $decorated;

    private $defaultFields = [
        'id' => 'id',
        'iso3' => 'iso3',
        'country' => 'country',
        'year' => 'year',
        'name' => 'name',
        'value' => 'value',
        'updated' => 'updated',
        'url' => 'url',
        'source' => 'source',
        'description' => 'description',
        'tags' => 'tags',
        'provider' => 'provider',
        'extra' => 'extra',
        'data' => 'data',
    ];

    public function __construct(NormalizerInterface $decorated)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->decorated->normalize($object, $format, $context);

        if (isset($data['extra'])) {
            if (is_array($data['extra'])) {
                $data += array_diff_key($data['extra'], $this->defaultFields);
            }
            unset($data['extra']);
        }

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        /** @var ApiPlatform\Metadata\Operations $operations */
        $operation = $context['operation'];
        $properties = $operation->getExtraProperties() ?? [];
        $provider = $properties['provider'] ?? NULL;

        // Multiple records.
        if (isset($data['data'])) {
            if (is_array($data['data'])) {
                foreach ($data['data'] as &$row) {
                    // Force provider.
                    if ($provider) {
                        $row['provider'] = $provider;
                    }

                    // Type conversion.
                    $row['year'] = (string) $row['year'];
                    $row['value'] = (string) $row['value'];

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

            // Type conversion.
            $data['year'] = (string) $data['year'];
            $data['value'] = (string) $data['value'];

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

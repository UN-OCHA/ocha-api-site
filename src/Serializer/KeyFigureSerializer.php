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
        // Check for any extra keys.
        if (!empty(array_diff_key($data, $this->defaultFields))) {
            // Move them into extra.
            $data['extra'] = $data['extra'] ?? [];
            $data['extra'] += array_diff_key($data, $this->defaultFields);
            $data = array_diff_key($data, $data['extra']);
        }
        //dd($data);
        return $this->decorated->denormalize($data, $type, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}

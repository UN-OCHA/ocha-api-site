<?php

namespace App\Serializer;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\ExternalLookup;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ExternalLookupDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private $iriConverter;

    public function __construct(IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = []) : mixed
    {
        // We need an operation.
        if (!isset($context['operation'])) {
            return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
        }

        /** @var \ApiPlatform\Metadata\HttpOperation $operation */
        $operation = $context['operation'];

        // Get extra properties.
        $properties = $operation->getExtraProperties() ?? [];

        // Check if we need to do something.
        if (!isset($properties['expand']) || $properties['expand'] !== 'key_figures') {
            return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
        }

        $data['provider'] = $properties['provider'];

        return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return \in_array($format, ['json', 'jsonld'], true) && is_a($type, ExternalLookup::class, true) && !isset($context[__CLASS__]);
    }
}

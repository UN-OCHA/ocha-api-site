<?php

namespace App\Serializer;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\HdxAdmin1;
use App\Entity\HdxLocation;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class HdxDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private $iriConverter;

    private $mapping = [
        'location_ref' => HdxLocation::class,
        'admin1_ref' => HdxAdmin1::class,
    ];

    public function __construct(IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = []) : mixed
    {
        foreach ($this->mapping as $field => $class_name) {
            if (isset($data[$field]) && is_numeric($data[$field])) {
                $data[$field] = $this->iriConverter->getIriFromResource(resource: $class_name, context: ['uri_variables' => ['id' => $data[$field]]]);
            }
        }

        return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return \in_array($format, ['json', 'jsonld'], true) && !isset($context[__CLASS__]);
    }
}

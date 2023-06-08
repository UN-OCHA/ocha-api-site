<?php

namespace App\Serializer;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Country;
use App\Entity\OchaPresence;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class OchaPresenceDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
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
        $data['countries'] = array_map(function ($id) {
            return $this->iriConverter->getIriFromResource(resource: Country::class, context: ['uri_variables' => ['id' => $id]]);
        }, $data['countries']);

//        $data['ocha_presence_external_ids'] = array_map(function ($id) {
//            return $this->iriConverter->getIriFromResource(resource: Country::class, context: ['uri_variables' => ['id' => $id]]);
//        }, $data['ocha_presence_external_ids']);

        return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return \in_array($format, ['json', 'jsonld'], true) && is_a($type, OchaPresence::class, true) && !empty($data['countries']) && !isset($context[__CLASS__]);
    }
}

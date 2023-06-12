<?php

namespace App\Serializer;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Country;
use App\Entity\OchaPresence;
use App\Entity\OchaPresenceExternalId;
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
        trigger_deprecation('denorm','v1', print_r($data,TRUE));
        if (isset($data['countries']) && is_array($data['countries'])) {
            foreach ($data['countries'] as &$country) {
                if (is_array($country)) {
                    if (isset($country['@id'])) {
                        $country['@id'] = $this->iriConverter->getIriFromResource(resource: Country::class, context: ['uri_variables' => ['id' => $country['@id']]]);
                    }
                    if (isset($country['id'])) {
                        $country['id'] = $this->iriConverter->getIriFromResource(resource: Country::class, context: ['uri_variables' => ['id' => $country['id']]]);
                    }
                }
                else {
                    $country = $this->iriConverter->getIriFromResource(resource: Country::class, context: ['uri_variables' => ['id' => $country]]);
                }
            }
        }

        if (isset($data['ocha_presence_external_ids'])) {
            $data['ocha_presence_external_ids'] = array_map(function ($id) {
                return $this->iriConverter->getIriFromResource(resource: OchaPresenceExternalId::class, context: ['uri_variables' => ['id' => $id]]);
            }, $data['ocha_presence_external_ids']);
        }

        trigger_deprecation('denorm','v2', print_r($data,TRUE));
        return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return \in_array($format, ['json', 'jsonld'], true) && is_a($type, OchaPresence::class, true) && !isset($context[__CLASS__]);
    }
}

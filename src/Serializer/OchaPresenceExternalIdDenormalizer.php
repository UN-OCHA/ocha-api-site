<?php

namespace App\Serializer;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\ExternalLookup;
use App\Entity\OchaPresence;
use App\Entity\OchaPresenceExternalId;
use App\Entity\Provider;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class OchaPresenceExternalIdDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
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
        if (isset($data['external_ids'])) {
            $data['external_ids'] = array_map(function ($id) {
              if (!is_string($id) || strpos($id, '/') !== FALSE) {
                  return $id;
              }
              return $this->iriConverter->getIriFromResource(resource: ExternalLookup::class, context: ['uri_variables' => ['id' => $id]]);
            }, $data['external_ids']);
        }

        if (isset($data['provider']) && is_string($data['provider']) && strpos($data['provider'], '/') === FALSE) {
            $data['provider'] = $this->iriConverter->getIriFromResource(resource: Provider::class, context: ['uri_variables' => ['id' => $data['provider']]]);
        }

        if (isset($data['ocha_presence']) && is_string($data['ocha_presence']) && strpos($data['ocha_presence'], '/') === FALSE) {
            $data['ocha_presence'] = $this->iriConverter->getIriFromResource(resource: OchaPresence::class, context: ['uri_variables' => ['id' => $data['ocha_presence']]]);
        }

        return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return \in_array($format, ['json', 'jsonld'], true) && is_a($type, OchaPresenceExternalId::class, true) && !isset($context[__CLASS__]);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            OchaPresenceExternalId::class => false,
        ];
    }
}

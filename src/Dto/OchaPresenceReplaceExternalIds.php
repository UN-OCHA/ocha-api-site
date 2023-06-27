<?php

namespace App\Dto;

final class OchaPresenceReplaceExternalIds
{
    public string $provider;
    public string $year;
    public array $externalIds = [];
}

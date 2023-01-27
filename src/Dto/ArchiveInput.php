<?php

namespace App\Dto;

class ArchiveInput {
    public function __construct(public string $iso3, public string $year) {}
}

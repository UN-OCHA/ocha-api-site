<?php

namespace App\Dto;

class SimpleStringObject {
    public function __construct(public string $label, public string $value) {}
}
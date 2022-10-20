<?php

namespace App\Entity;

class SimpleStringObject {
    public function __construct(public string $label, public string $value) {}
}
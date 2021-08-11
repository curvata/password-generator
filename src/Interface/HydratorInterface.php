<?php

namespace App\Interface;

use App\Class\Password;

interface HydratorInterface
{
    public function hydrate(array $hydrate): Password;
}

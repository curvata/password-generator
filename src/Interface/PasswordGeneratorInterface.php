<?php

namespace App\Interface;

use App\Class\Password;

interface PasswordGeneratorInterface
{
    public function generate(Password $password): array;
}

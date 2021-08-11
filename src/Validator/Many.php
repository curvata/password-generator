<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Many extends Constraint
{

    const MIN = 1;
    const MAX = 10;

    public $message = "Vous pouvez générer entre %min% et %max% mot de passe";
}

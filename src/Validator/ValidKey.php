<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidKey extends Constraint
{

    const KEY = ["int", "upper", "length", "symbol", "many"];

    public $message = "La clé '%key%' n'est pas une configuration valide";
}

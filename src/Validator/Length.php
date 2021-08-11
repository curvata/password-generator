<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Length extends Constraint
{

    const MIN = 5;
    const MAX = 32;

    public $message = "Vous devez configurer une taille de mot de passe compris entre %min% et maximum %max% caractères";
}

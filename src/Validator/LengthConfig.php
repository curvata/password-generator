<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class LengthConfig extends Constraint
{

    const MIN = 5;
    const MAX = 32;

    public $message = "La longueur maximale défini pour le mot de passe est de %length% caractères et votre configuration totale est de %allConfig% caractères";
}

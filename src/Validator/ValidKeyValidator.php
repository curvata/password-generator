<?php

namespace App\Validator;

use App\Validator\ValidKey;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidKeyValidator extends ConstraintValidator
{

    /**
     * Vérifie que les clés de configuration soient valides
     */
    public function validate(mixed $data, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidKey) {
            throw new UnexpectedTypeException($constraint, ValidKey::class);
        }

        if (empty($data) || '' === $data) {
            return;
        }

        foreach ($data as $key => $value) {
            if (!in_array($key, $constraint::KEY)) {
                $this->context->buildViolation($constraint->message)
                ->setParameter("%key%", $key)->addViolation();
            }
        }
    }
}

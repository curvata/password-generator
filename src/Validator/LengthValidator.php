<?php

namespace App\Validator;

use App\Validator\Length;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LengthValidator extends ConstraintValidator
{

    /**
     * La longueur du mot de passe doit contenir entre 5 et 32 caractères
     */
    public function validate(mixed $data, Constraint $constraint): void
    {
        if (!$constraint instanceof Length) {
            throw new UnexpectedTypeException($constraint, Length::class);
        }

        if (empty($data) || '' === $data) {
            return;
        }

        if (isset($data['length'])) {
            if ($data['length'] > $constraint::MAX || $data['length'] < $constraint::MIN) {
                $this->context->buildViolation($constraint->message)
                ->setParameters(["%min%" => $constraint::MIN, "%max%" => $constraint::MAX])->addViolation();
                return;
            }
        }
    }
}

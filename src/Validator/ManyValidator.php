<?php

namespace App\Validator;

use App\Validator\Many;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ManyValidator extends ConstraintValidator
{

    /**
     * Le nombre de mot de passe généré doit être entre 1 et 10
     */
    public function validate(mixed $data, Constraint $constraint): void
    {
        if (!$constraint instanceof Many) {
            throw new UnexpectedTypeException($constraint, Many::class);
        }

        if (empty($data) || '' === $data) {
            return;
        }

        if (isset($data['many'])) {
            if ($data['many'] < $constraint::MIN || $data['many'] > $constraint::MAX) {
                $this->context->buildViolation($constraint->message)
                ->setParameters(["%min%" => $constraint::MIN, "%max%" => $constraint::MAX])->addViolation();
                return;
            }
        }
    }
}

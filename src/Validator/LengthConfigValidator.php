<?php

namespace App\Validator;

use App\Validator\Many;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LengthConfigValidator extends ConstraintValidator
{

    /**
     * La longueur de la configuration doit être inférieure à la longueur du mot de passe
     */
    public function validate(mixed $data, Constraint $constraint): void
    {
        if (!$constraint instanceof LengthConfig) {
            throw new UnexpectedTypeException($constraint, LengthConfig::class);
        }

        if (empty($data) || '' === $data) {
            return;
        }

        $allConfig = 0;

        foreach ($data as $key => $value) {
            ($key != "length" && $key != "many")? $allConfig += $data[$key]  : "";
        }

        if (isset($data['length'])) {
            if ($allConfig > $data['length']) {
                $this->context->buildViolation($constraint->message)
                ->setParameters(["%length%" => $data['length'], "%allConfig%" => $allConfig])->addViolation();
                return;
            }
        } elseif ($allConfig > $constraint::MIN) {
            $this->context->buildViolation($constraint->message)
            ->setParameters(["%length%" => $constraint::MIN, "%allConfig%" => $allConfig])->addViolation();
            return;
        }
    }
}

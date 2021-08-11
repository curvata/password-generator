<?php

namespace App\Class;

use App\Interface\PasswordGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;

class PasswordGenerator implements PasswordGeneratorInterface
{
    const LETTER = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
    const SYMBOLS = ["!", "@", "#", "$", "%", "&", "*", "+", "=", "?", "_"];
    
    /**
     * Génère le mot de passe
     */
    public function generate(Password $password): array
    {
        $passwordGenerate = [];

        for ($a = 1; $a <= $password->getMany(); $a++) {
            $int = $password->getInt();
            $upper = $password->getUpper();
            $symbol = $password->getSymbol();

            for ($b = 1; $b <= $password->getLength(); $b++) {
                if ($int > 0) {
                    $passwordGenerate[$a][] = random_int(0, 9);
                    $int--;
                    continue;
                }

                if ($symbol > 0) {
                    $passwordGenerate[$a][] = self::SYMBOLS[random_int(0, count(self::SYMBOLS)-1)];
                    $symbol--;
                    continue;
                }

                if ($upper > 0) {
                    $passwordGenerate[$a][] = strtoupper(self::LETTER[random_int(0, count(self::LETTER)-1)]);
                    $upper--;
                    continue;
                }
                $passwordGenerate[$a][] = self::LETTER[random_int(0, count(self::LETTER)-1)];
            }

            shuffle($passwordGenerate[$a]);
        }

        foreach ($passwordGenerate as $key => $value) {
            $passwordGenerate[$key] = implode("", $passwordGenerate[$key]);
        }

        return $passwordGenerate;
    }
}

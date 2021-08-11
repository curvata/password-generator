<?php

namespace App\Class;

use App\Interface\HydratorInterface;
use Exception;

class Hydrator implements HydratorInterface
{

    /**
     * Hydrate l'objet Password
     */
    public function hydrate(array $array): Password
    {
        $password = new Password();

        foreach ($array as $key => $value) {
            $set = "set". strtoupper($key[0]) . substr($key, 1);
            try {
                $password->$set((int)$value);
            } catch (Exception $e) {
                return 'Exception reçue : '.  $e->getMessage();
            }
        }

        return $password;
    }
}

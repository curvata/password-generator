<?php

namespace App\Controller;

use App\Interface\HydratorInterface;
use App\Interface\PasswordGeneratorInterface;
use App\Validator\Length;
use App\Validator\LengthConfig;
use App\Validator\Many;
use App\Validator\ValidKey;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GenerateController extends AbstractController
{
    /**
     * Entrée de l'API
     */
    #[Route('/api/v1/generate', name: 'generate', methods:['GET'])]
    public function index(Request $request, ValidatorInterface $validator, PasswordGeneratorInterface $passwordGenerator, HydratorInterface $hydrator): JsonResponse
    {
        $timeZone = new DateTimeZone('Europe/Paris');
        $content = $request->query->all();
        
        $config = [
            'Content-Type' => "application/json",
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET'
        ];

        $violations = $validator->validate($content, [
            new ValidKey(),
            new Length(),
            new LengthConfig(),
            new Many(),
        ]);

        if (count($violations) > 0) {
            $messages = [];

            foreach ($violations as $value) {
                $messages[] = $value->getMessage();
            }
            return new JsonResponse(
                [
                    "success" => false,
                    "erreur" => ["message" => implode(", ", $messages)],
                    "date" => (new DateTime('now', $timeZone))->format("d-m-Y H:i:s")
                ],
                400,
                $config
            );
        }

        $password = $hydrator->hydrate($content);
        
        return new JsonResponse(
            [
                "success" => true,
                "data" => $passwordGenerator->generate($password),
                "created_at" => (new DateTime('now', $timeZone))->format("d-m-Y H:i:s")
            ],
            200,
            $config
        );
    }
}

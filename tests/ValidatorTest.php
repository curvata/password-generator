<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ValidatorTest extends WebTestCase
{       
    public function jsonDecode(string $value)
    {
        $serializer = $this->getContainer()->get('serializer.encoder.json');
        return $serializer->decode($value, "");
    }

    public function getRegex(int $int, int $upper, int $symbol, int $length): string
    {
        $regex = "";

        $regexLength = "(?=^.{".$length."}$)";

        if ($int > 0) {
            $regex .= "(?=";
            for ($a = 0; $a < $int; $a++) {
                $regex .= ".*[\d]";
            }
            $regex .= ")";
        } else {
            $regex .= "(?!.*[\d])";
        }

        if ($upper > 0) {
            $regex .= "(?=";
            for ($a = 0; $a < $upper; $a++) {
                $regex .= ".*[A-Z]";
            }
            $regex .= ")";
        } else {
            $regex .= "(?!.*[A-Z])";
        }

        if ($symbol > 0) {
            $regex .= "(?=";
            for ($a = 0; $a < $symbol; $a++) {
                $regex .= ".*[!@#%&*+=\$?\_]";
            }
            $regex .= ")";
        } else {
            $regex .= "(?!.*[!@#%&*+=\$?\_])";
        }

        ($length > 0)? $regex .= $regexLength:"";

        return "/".$regex."/";
    }

    /**
     * Le mot de passe par défaut contient 5 caractères minuscules
     */
    public function testDefaultGeneratePassword(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $arr = $this->jsonDecode($response->getContent());
        $this->assertTrue($arr['success']);
        $this->assertMatchesRegularExpression($this->getRegex(0, 0, 0, 5), $arr['data'][1]);
    }

    /**
     * Si la clé de configuration est invalide
     */
    public function testInvalidConfigKey(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['toto' => 5]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "La clé 'toto' n'est pas une configuration valide");
   
    }
    
    /**
     * Le mot de passe contient des chiffres
     */
    public function testConfigInt(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['int' => 2]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $arr = $this->jsonDecode($response->getContent());
        $this->assertTrue($arr['success']);
        $this->assertMatchesRegularExpression($this->getRegex(2, 0, 0, 5), $arr['data'][1]);
    }
    
    /**
     * Le nombre de caractère défini dans la configuration 'int' ne doit pas dépasser la longueur du mot de passe
     */
    public function testConfigIntToLong(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['int' => 6]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "La longueur maximale défini pour le mot de passe est de 5 caractères et votre configuration totale est de 6 caractères");
    }
    
    /**
     * Le mot de passe contient des majuscules
     */
    public function testConfigUpper(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['upper' => 3]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $arr = $this->jsonDecode($response->getContent());
        $this->assertTrue($arr['success']);
        $this->assertMatchesRegularExpression($this->getRegex(0, 3, 0, 5), $arr['data'][1]);
    }
    
    /**
     * Le nombre de caractère défini dans la configuration 'upper' ne doit pas dépasser la longueur du mot de passe
     */
    public function testConfigUpperToLong(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['upper' => 6]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "La longueur maximale défini pour le mot de passe est de 5 caractères et votre configuration totale est de 6 caractères");
    }
    
    /**
     * Le mot de passe contient des symboles
     */
    public function testConfigSymbol(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['symbol' => 4]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $arr = $this->jsonDecode($response->getContent());
        $this->assertTrue($arr['success']);
        
        $this->assertMatchesRegularExpression($this->getRegex(0, 0, 4, 5), $arr['data'][1]);
    }
    
    /**
     * Le nombre de caractère défini dans la configuration 'symbol' ne doit pas dépasser la longueur du mot de passe
     */
    public function testConfigSymbolToLong(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['symbol' => 6]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "La longueur maximale défini pour le mot de passe est de 5 caractères et votre configuration totale est de 6 caractères");
    }
    
    /**
     * La clé de configuration pour la longueur du mot de passe doit ètre supérieur à 5 caractères
     */
    public function testConfigLengthMin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['length' => 4]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "Vous devez configurer une taille de mot de passe compris entre 5 et maximum 32 caractères");
    }
    
    /**
     * La clé de configuration pour la longueur du mot de passe doit ètre inférieur à 32 caractères
     */
    public function testConfigLengthMax(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['length' => 33]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "Vous devez configurer une taille de mot de passe compris entre 5 et maximum 32 caractères");
    }
    
    /**
     * L'ensemble de la configuration ne doit pas être supérieur à 'length'
     */
    public function testAllConfig(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['length' => 10, "int" => 2, "upper" => 5, "symbol" => 3]);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $arr = $this->jsonDecode($response->getContent());
        $this->assertTrue($arr['success']);

        $this->assertMatchesRegularExpression($this->getRegex(2, 5, 3, 10), $arr['data'][1]);
    }
    
    /**
     * Si l'ensemble de la configuration est supérieur à 'length'
     */
    public function testAllConfigToLong(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['length' => 30, "int" => 15, "upper" => 10, "symbol" => 10]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "La longueur maximale défini pour le mot de passe est de 30 caractères et votre configuration totale est de 35 caractères");
    }
    
    /**
     * Vérifie le nombre de mot de passe généré
     */
    public function testConfigMany(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['many' => 10]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(200);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertTrue($arr['success']);
        $this->assertCount(10, $arr['data']);
    }
    
    /**
     * Le nombre de mot de passe généré doit être entre 1 et 10
     */
    public function testConfigManyToLong(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate', ['many' => 11]);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $arr = $this->jsonDecode($response->getContent());
        $this->assertFalse($arr['success']);
        $this->assertStringContainsString($arr['erreur']['message'], "Vous pouvez générer entre 1 et 10 mot de passe");
    }
}

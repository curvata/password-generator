<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{   
    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            1,
            $crawler->filter('h2:contains("GENERATOR")')->count());
        $this->assertEquals(
            1,
            $crawler->filter('h2:contains("CONFIGURATION API")')->count());
        $this->assertEquals(
            1,
            $crawler->filter('p:contains("https://password-generator.menezes.be/api/v1/generate")')->count());
        
    }
}

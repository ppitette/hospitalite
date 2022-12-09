<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testShouldBeDisplayLoginPage(): void
    {
        $client = static::createClient();
        $client = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Authentification');
    }
}

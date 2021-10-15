<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group integration
 */
class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        // Request a specific page
        $crawler = $client->request('GET', '/');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello HomeController!');
    }
}

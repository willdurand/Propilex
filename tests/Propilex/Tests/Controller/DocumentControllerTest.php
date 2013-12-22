<?php

namespace Propilex\Tests\Controller;

use Silex\WebTestCase;

class DocumentRestControllerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/propilex.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return include_once __DIR__ . '/../../../../app/stack.php';
    }

    public function testGetDocuments()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/documents');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);

        $data = json_decode($response->getContent(), true);
        foreach ($data as $item) {
            $this->assertValidDocument($item);
        }
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    protected function assertValidDocument($document)
    {
        $this->arrayHasKey('id', $document);
        $this->arrayHasKey('title', $document);
        $this->arrayHasKey('body', $document);
        $this->arrayHasKey('created_at', $document);
    }
}

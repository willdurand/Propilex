<?php

namespace Propilex\Tests;

use Silex\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class WebTestCase extends BaseWebTestCase
{
    protected function assertJsonResponse(Response $response, $statusCode = 200)
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

    protected function assertValidDocument(array $document)
    {
        $this->assertArrayHasKey('id', $document);
        $this->assertArrayHasKey('title', $document);
        $this->assertArrayHasKey('body', $document);
        $this->assertArrayHasKey('created_at', $document);
        $this->assertArrayHasKey('updated_at', $document);
        $this->assertArrayHasKey('_links', $document);

        $this->assertCount(6, $document);
    }
}

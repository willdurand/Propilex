<?php

namespace Propilex\Tests;

use Silex\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class WebTestCase extends BaseWebTestCase
{
    protected function assertJsonResponse(Response $response, $statusCode = 200, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );
    }

    protected function assertJsonErrorResponse(Response $response, $statusCode = 200)
    {
        $this->assertJsonResponse($response, $statusCode, 'application/vnd.error+json');
    }

    protected function assertHalJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertJsonResponse($response, $statusCode, 'application/hal+json');
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

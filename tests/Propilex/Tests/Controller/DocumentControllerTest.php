<?php

namespace Propilex\Tests\Controller;

use Propilex\Model\Document;
use Propilex\Model\Repository\InMemoryDocumentRepository;
use Propilex\Tests\WebTestCase;

class DocumentRestControllerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/propilex.php';
        $app['debug'] = true;

        $app['document_repository'] = $app->share(function () {
            return new InMemoryDocumentRepository([
                $this->createDocument(1, 'foo', 'bar'),
                $this->createDocument(2, 'baz', 'bim'),
                $this->createDocument(3, 'baz', 'noo'),
            ]);
        });

        return include __DIR__ . '/../../../../app/stack.php';
    }

    public function testListDocuments()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/documents');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('_embedded', $data);
        $this->assertArrayHasKey('_links', $data);
        $this->assertArrayHasKey('page', $data);
        $this->assertArrayHasKey('pages', $data);
        $this->assertArrayHasKey('limit', $data);

        $this->assertArrayHasKey('documents', $data['_embedded']);
        $documents = $data['_embedded']['documents'];

        $this->assertCount(3, $documents);
        $this->assertEquals(1, $data['page']);
        $this->assertEquals(1, $data['pages']);
        $this->assertEquals(10, $data['limit']);

        foreach ($documents as $item) {
            $this->assertValidDocument($item);
        }

        // cache
        $this->assertTrue($response->isValidateable());
        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertFalse($response->headers->has('Last-Modified'));
        $this->assertTrue($response->headers->has('ETag'));

        // links
        $links = $data['_links'];
        $this->assertArrayHasKey('self', $links);
        $this->assertArrayHasKey('curies', $links);
        $this->assertArrayHasKey('first', $links);
        $this->assertArrayHasKey('last', $links);
        $this->assertArrayNotHasKey('next', $links);
        $this->assertArrayNotHasKey('previous', $links);

        $this->assertEquals('p', $links['curies'][0]['name']);
        $this->assertEquals('http://localhost/rels/{rel}', $links['curies'][0]['href']);
    }

    public function testListDocumentsIsPaginated()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/documents?limit=1');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('_embedded', $data);
        $this->assertArrayHasKey('page', $data);
        $this->assertArrayHasKey('pages', $data);
        $this->assertArrayHasKey('limit', $data);

        $this->assertArrayHasKey('documents', $data['_embedded']);
        $documents = $data['_embedded']['documents'];

        $this->assertCount(1, $documents);
        $this->assertEquals(1, $data['page']);
        $this->assertEquals(3, $data['pages']);
        $this->assertEquals(1, $data['limit']);

        $this->assertValidDocument(current($documents));

        // links
        $links = $data['_links'];
        $this->assertArrayHasKey('self', $links);
        $this->assertArrayHasKey('curies', $links);
        $this->assertArrayHasKey('first', $links);
        $this->assertArrayHasKey('last', $links);
        $this->assertArrayHasKey('next', $links);
        $this->assertArrayNotHasKey('previous', $links);

        $this->assertEquals('p', $links['curies'][0]['name']);
        $this->assertEquals('http://localhost/rels/{rel}', $links['curies'][0]['href']);
    }

    public function testGetDocument()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/documents/1');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);

        $data = json_decode($response->getContent(), true);

        $this->assertValidDocument($data);

        // cache
        $this->assertTrue($response->isValidateable());
        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertTrue($response->headers->has('Last-Modified'));
    }

    public function testGetUnknownDocumentReturns404()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/documents/123');
        $response = $client->getResponse();

        $this->assertJsonErrorResponse($response, 404);

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Document with id = "123" does not exist.', $data['message']);
    }

    public function testErrorMessagesShouldBeTranslated()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/documents/123', [], [], [
            'HTTP_Accept-Language' => 'fr',
            ]);
        $response = $client->getResponse();

        $this->assertJsonErrorResponse($response, 404);

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Le document avec id = "123" n\'existe pas.', $data['message']);
    }

    public function testPostJsonData()
    {
        $client   = static::createClient();
        $crawler  = $client->request('POST', '/documents', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], <<<JSON
{
    "title": "Hello, World",
    "body": "That's my body."
}
JSON
        );

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 201);
        $this->assertTrue($response->headers->has('Location'));

        $document = json_decode($response->getContent(), true);

        $this->assertValidDocument($document);
    }

    public function testPostXmlData()
    {
        $client   = static::createClient();
        $crawler  = $client->request('POST', '/documents', [], [], [
            'CONTENT_TYPE' => 'application/xml',
        ], <<<XML
<document>
    <title>Hello, You</title>
    <body>That's my body</body>
</document>
XML
        );

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 201);

        $document = json_decode($response->getContent(), true);

        $this->assertValidDocument($document);
        $this->assertEquals('Hello, You', $document['title']);

        $this->assertTrue($response->headers->has('Location'));
        $this->assertEquals(
            sprintf('http://localhost/documents/%d', $document['id']),
            $response->headers->get('Location')
        );
    }

    public function testPostInvalidXmlData()
    {
        $client   = static::createClient();
        $crawler  = $client->request('POST', '/documents', [], [], [
            'CONTENT_TYPE' => 'application/xml',
        ], <<<XML
<document>
    <title>Hello, You</title>
</document>
XML
        );

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 400);

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('errors', $data);
        $this->assertCount(1, $data['errors']);

        $this->assertEquals('body', $data['errors'][0]['field']);
        $this->assertEquals('This value should not be blank.', $data['errors'][0]['message']);
    }

    public function testPostInvalidJsonData()
    {
        $client   = static::createClient();
        $crawler  = $client->request('POST', '/documents', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Accept'  => 'application/xml',
            ], <<<JSON
{
    "body": "foo"
}
JSON
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<errors>
  <error field="title">
    <message><![CDATA[This value should not be blank.]]></message>
  </error>
</errors>

XML
        , $response->getContent());
    }

    public function testPutXmlData()
    {
        $client   = static::createClient();
        $crawler  = $client->request('PUT', '/documents/1', [], [], [
            'CONTENT_TYPE' => 'application/xml',
        ], <<<XML
<document>
    <title>foo</title>
    <body>That's my body</body>
</document>
XML
        );

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);

        $document = json_decode($response->getContent(), true);

        $this->assertValidDocument($document);

        $this->assertEquals(1, $document['id']);
        $this->assertEquals('foo', $document['title']);
        $this->assertEquals('That\'s my body', $document['body']);

        $this->assertFalse($response->headers->has('Location'));
    }

    public function testPutJsonData()
    {
        $client   = static::createClient();
        $crawler  = $client->request('PUT', '/documents/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Accept'  => 'application/xml',
        ], <<<JSON
{
    "title": "foo",
    "body": "That's my body"
}
JSON
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'));

        $this->assertContains(<<<XML
  <id>1</id>
  <title><![CDATA[foo]]></title>
  <body><![CDATA[That's my body]]></body>
XML
        , $response->getContent());
    }

    public function testPutIsProtectedAgainstMassAssignement()
    {
        $client   = static::createClient();
        $crawler  = $client->request('PUT', '/documents/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Accept'  => 'application/xml',
        ], <<<JSON
{
    "id": 123,
    "title": "foo",
    "body": "That's my body"
}
JSON
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'));

        $this->assertContains(<<<XML
  <id>1</id>
  <title><![CDATA[foo]]></title>
  <body><![CDATA[That's my body]]></body>
XML
        , $response->getContent());
    }

    public function testDelete()
    {
        $client   = static::createClient();
        $crawler  = $client->request('DELETE', '/documents/1');
        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }

    private function createDocument($id, $title, $body)
    {
        $document = new Document();
        $document->fromArray([
            'id'         => $id,
            'title'      => $title,
            'body'       => $body,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
            ]);

        return $document;
    }
}

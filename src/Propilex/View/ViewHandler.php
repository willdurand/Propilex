<?php

namespace Propilex\View;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewHandler
{
    private $serializer;

    private $request;

    public function __construct(SerializerInterface $serializer, Request $request)
    {
        $this->serializer = $serializer;
        $this->request    = $request;
    }

    public function handle($data, $statusCode = 200, array $headers = [], Response $response = null)
    {
        $format   = $this->request->attributes->get('_format');
        $mimeType = $this->request->attributes->get('_mime_type');

        if (empty($format) || 'html' === $format) {
            $format   = 'json';
            $mimeType = 'application/json';
        }

        if (null === $response) {
            $response = new Response();
        }

        if (in_array($statusCode, [ 404, 500 ])) {
            switch ($format) {
                case 'xml':
                    $mimeType = 'application/vnd.error+xml';
                    break;

                default:
                    $mimeType = 'application/vnd.error+json';
            }
        }

        $response->setContent($this->serializer->serialize($data, $format));
        $response->setStatusCode($statusCode);
        $response->headers->add(array_merge(
            [ 'Content-Type' => $mimeType ],
            $headers
        ));

        return $response;
    }
}

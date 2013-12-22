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

    public function handle($data, $statusCode = 200, array $headers = [])
    {
        $format   = $this->request->attributes->get('_format');
        $mimeType = $this->request->attributes->get('_mime_type');

        if (empty($format) || 'html' === $format) {
            $format   = 'json';
            $mimeType = 'application/json';
        }

        return new Response(
            $this->serializer->serialize($data, $format),
            $statusCode,
            array_merge([ 'Content-Type' => $mimeType ], $headers)
        );
    }
}

<?php

namespace Propilex\View;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewHandler
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function handle(Request $request, $data, $statusCode = 200, array $headers = [])
    {
        $format   = $request->attributes->get('_format');
        $mimeType = $request->attributes->get('_mime_type');

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

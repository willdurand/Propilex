<?php

namespace Propilex\View;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class ViewHandler
{
    private $serializer;

    private $request;

    private $acceptableMimeTypes;

    public function __construct(SerializerInterface $serializer, Request $request, array $acceptableMimeTypes)
    {
        $this->serializer           = $serializer;
        $this->request              = $request;
        $this->acceptableMimeTypes  = $acceptableMimeTypes;
    }

    public function handle($data, $statusCode = 200, array $headers = [], Response $response = null)
    {
        $format   = $this->request->attributes->get('_format');
        $mimeType = $this->request->attributes->get('_mime_type');

        if (!in_array($mimeType, $this->acceptableMimeTypes)) {
            throw new NotAcceptableHttpException(sprintf(
                'Mime type "%s" is not supported. Supported mime types are: %s.',
                $mimeType,
                implode(', ', $this->acceptableMimeTypes)
            ));
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

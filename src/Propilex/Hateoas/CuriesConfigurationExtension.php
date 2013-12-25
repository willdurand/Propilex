<?php

namespace Propilex\Hateoas;

use Hateoas\Configuration\Relation;
use Hateoas\Configuration\Route;
use Hateoas\Configuration\Metadata\ClassMetadataInterface;
use Hateoas\Configuration\Metadata\ConfigurationExtensionInterface;

class CuriesConfigurationExtension implements ConfigurationExtensionInterface
{
    private $routeName;

    private $generatorName;

    public function __construct($routeName, $generatorName)
    {
        $this->routeName     = $routeName;
        $this->generatorName = $generatorName;
    }

    /**
     * {@inheritDoc}
     */
    public function decorate(ClassMetadataInterface $classMetadata)
    {
        $classes = [
            'Propilex\View\Endpoint',
            'Hateoas\Representation\CollectionRepresentation',
        ];

        if (!in_array($classMetadata->getName(), $classes)) {
            return;
        }

        $classMetadata->addRelation(
            new Relation(
                'curies',
                new Route(
                    $this->routeName,
                    [ 'rel' => '{rel}' ]
                    ,
                    true,
                    $this->generatorName
                ),
                null,
                [
                    'name'      => "expr(curies_prefix)",
                    'templated' => true,
                ]
            )
        );
    }
}

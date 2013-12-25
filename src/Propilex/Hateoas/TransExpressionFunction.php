<?php

namespace Propilex\Hateoas;

use Hateoas\Expression\ExpressionFunctionInterface;
use Symfony\Component\Translation\Translator;

class TransExpressionFunction implements ExpressionFunctionInterface
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'trans';
    }

    /**
     * {@inheritDoc}
     */
    public function getCompiler()
    {
        return function ($id, array $parameters = array()) {
            return sprintf('$translator->trans(%s, %s)', $id, $parameters);
        };
    }

    /**
     * {@inheritDoc}
     */
    public function getEvaluator()
    {
        return function ($context, $id, array $parameters = array()) {
            return $context['translator']->trans($id, $parameters);
        };
    }

    /**
     * {@inheritDoc}
     */
    public function getContextVariables()
    {
        return array('translator' => $this->translator);
    }
}

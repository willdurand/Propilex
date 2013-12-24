<?php

return (new Stack\Builder())
    ->push('Negotiation\Stack\Negotiation', $app['format.negotiator'])
    ->resolve($app);

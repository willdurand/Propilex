<?php

$conf = require __DIR__ . '/conf/Propilex-conf.php';

foreach ($conf['datasources'] as $name => $configuration) {
    if (!is_array($configuration)) {
        continue;
    }

    $conf['datasources'][$name]['connection']['dsn'] = strtr(
        $configuration['connection']['dsn'],
        [ '%CACHE_DIR%' => realpath(__DIR__ . '/../../cache') ]
    );
}

return $conf;

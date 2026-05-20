<?php
require __DIR__ . '/vendor/autoload.php';

$openapi = (new \OpenApi\Generator())->generate([__DIR__ . '/app/Http/Controllers/Controller.php']);
echo $openapi->toYaml();

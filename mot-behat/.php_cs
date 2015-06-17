<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in([__DIR__.'/features', __DIR__.'/src'])
;

return Symfony\CS\Config\Config::create()
    ->fixers(array('strict_param', 'short_array_syntax'))
    ->finder($finder)
;

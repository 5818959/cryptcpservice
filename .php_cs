<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('vendor')
    ->exclude('spec')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->fixers(array('-concat_without_spaces'))
    ->finder($finder)
;

<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new Finder())
    ->files()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/database/migrations')
    ->in(__DIR__ . '/database/seeders')
    ->in(__DIR__ . '/tests');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'new_with_parentheses' => ['anonymous_class' => true],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => false,
            'import_functions' => false,
        ],
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'single_line_throw' => true,
    ])

    ->setFinder($finder)

    ->setParallelConfig(ParallelConfigFactory::detect())
;

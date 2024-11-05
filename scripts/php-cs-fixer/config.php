<?php

use Symfony\Component\Finder\SplFileInfo;

use Aeliot\PhpCsFixerBaseline\Service\FilterFactory;

Phar::loadPhar(dirname(__DIR__, 2) . '/tools/pcsf-baseline.phar', 'pcsf-baseline.phar');
require_once 'phar://pcsf-baseline.phar/vendor/autoload.php';

$rules = [
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'concat_space' => [
        'spacing' => 'one',
    ],
    'header_comment' => [
        'header' => <<<'EOF'
            This file is part of the Doctrine Encrypted Field Bundle.

            (c) Anatoliy Melnikov <5785276@gmail.com>

            This source file is subject to the MIT license that is bundled
            with this source code in the file LICENSE.
            EOF,
    ],
    'phpdoc_align' => ['align' => 'left'],
    // detected errors
    'global_namespace_import' => false,
    'no_multiline_whitespace_around_double_arrow' => false,
    'nullable_type_declaration_for_default_null_value' => false,
    'php_unit_method_casing' => false,
    'php_unit_test_annotation' => false,
    'phpdoc_separation' => false,
    'single_line_throw' => false,
    'types_spaces' => false,
];

$config = (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setCacheFile(dirname(__DIR__, 2) . '/var/.php-cs-fixer.cache')
    ->setRules($rules);

/** @var PhpCsFixer\Finder $finder */
$finder = require __DIR__ . '/finder.php';
$finder->filter((new FilterFactory())->createFilter(__DIR__ . '/.php-cs-fixer-baseline.json', $config));

return $config->setFinder($finder);

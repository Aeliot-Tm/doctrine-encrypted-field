<?php

use Symfony\Component\Finder\SplFileInfo;

require_once __DIR__ . '/.php-cs-fixer-baseline.php';

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
    ->setRules($rules);

$baseline = cs_fixer_get_baseline_hashes($config);
/** @var PhpCsFixer\Finder $finder */
$finder = require __DIR__ . '/.php-cs-fixer-finder.php';
$finder->filter(static function (SplFileInfo $file) use ($baseline): bool {
    $pathname = $file->getPathname();
    $hash = ($baseline[$pathname] ?? [])['hash'] ?? null;

    return (null === $hash) || (cs_fixer_get_path_hash($pathname) !== $hash);
});

return $config->setFinder($finder);

<?php

declare(strict_types=1);

return $finder = (new PhpCsFixer\Finder())
    ->files()
    ->ignoreVCS(true)
    ->in(dirname(__DIR__, 2))
    ->exclude(['var', 'vendor'])
    ->append([
        dirname(__DIR__, 2) . '/bin/dev/php-cs-fixer-update-baseline',
    ]);

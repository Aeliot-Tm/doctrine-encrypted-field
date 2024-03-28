<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Doctrine\DBAL\Connection;

interface ConnectionPreparerInterface
{
    public function prepareConnection(Connection $connection): void;

    public function wrapParameter(string $sqlExpr): string;
}

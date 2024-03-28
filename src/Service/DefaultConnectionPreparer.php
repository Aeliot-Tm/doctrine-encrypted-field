<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Doctrine\DBAL\Connection;

final class DefaultConnectionPreparer implements ConnectionPreparerInterface
{
    public function prepareConnection(Connection $connection): void
    {
    }

    public function wrapParameter(string $sqlExpr): string
    {
        return $sqlExpr;
    }
}

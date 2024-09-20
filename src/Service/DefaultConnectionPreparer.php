<?php

declare(strict_types=1);

/*
 * This file is part of the Doctrine Encrypted Field Bundle.
 *
 * (c) Anatoliy Melnikov <5785276@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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

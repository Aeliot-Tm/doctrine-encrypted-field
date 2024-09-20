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

namespace Aeliot\Bundle\DoctrineEncryptedField\Doctrine;

use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;

trait EncryptionExpressionTrait
{
    private function getEncryptSQLExpression(string $sqlExpr): string
    {
        return sprintf('%s(%s)', FunctionEnum::ENCRYPT, $this->normalizeSqlExpr($sqlExpr));
    }

    private function getDecryptSQLExpression(string $sqlExpr): string
    {
        return sprintf('%s(%s)', FunctionEnum::DECRYPT, $this->normalizeSqlExpr($sqlExpr));
    }

    private function normalizeSqlExpr(string $sqlExpr): string
    {
        return $sqlExpr ?: 'NULL';
    }
}

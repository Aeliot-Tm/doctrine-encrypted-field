<?php

declare(strict_types=1);

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

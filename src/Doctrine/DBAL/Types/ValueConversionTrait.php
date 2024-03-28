<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\EncryptionExpressionTrait;
use Doctrine\DBAL\Platforms\AbstractPlatform;

trait ValueConversionTrait
{
    use EncryptionExpressionTrait;

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    /**
     * @param string $sqlExpr
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        return $this->getEncryptSQLExpression($sqlExpr);
    }

    /**
     * @param string $sqlExpr
     * @param AbstractPlatform $platform
     */
    public function convertToPHPValueSQL($sqlExpr, $platform): string
    {
        return $this->getDecryptSQLExpression($sqlExpr);
    }
}

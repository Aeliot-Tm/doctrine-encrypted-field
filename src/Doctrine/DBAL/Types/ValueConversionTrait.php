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

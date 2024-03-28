<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

interface EncryptedFieldLengthInterface
{
    public function getDefaultFieldLength(AbstractPlatform $platform): ?int;
}

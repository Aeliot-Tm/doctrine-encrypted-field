<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Enum;

final class FieldTypeEnum
{
    public const ENCRYPTED_STRING = 'encrypted_string';

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::ENCRYPTED_STRING,
        ];
    }

    private function __construct()
    {
    }
}

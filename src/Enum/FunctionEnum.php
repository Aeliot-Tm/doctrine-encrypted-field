<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Enum;

final class FunctionEnum
{
    public const DECRYPT = 'APP_DECRYPT';
    public const ENCRYPT = 'APP_ENCRYPT';
    public const GET_ENCRYPTION_KEY = 'APP_GET_ENCRYPTION_KEY';

    private function __construct()
    {
    }
}

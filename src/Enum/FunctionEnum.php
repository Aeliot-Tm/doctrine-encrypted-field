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

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

final class FieldTypeEnum
{
    public const ENCRYPTED_DATE = 'encrypted_date';
    public const ENCRYPTED_DATE_IMMUTABLE = 'encrypted_date_immutable';
    public const ENCRYPTED_DATETIME = 'encrypted_datetime';
    public const ENCRYPTED_DATETIME_IMMUTABLE = 'encrypted_datetime_immutable';
    public const ENCRYPTED_JSON = 'encrypted_json';
    public const ENCRYPTED_STRING = 'encrypted_string';
    public const ENCRYPTED_TEXT = 'encrypted_text';

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::ENCRYPTED_DATE,
            self::ENCRYPTED_DATE_IMMUTABLE,
            self::ENCRYPTED_DATETIME,
            self::ENCRYPTED_DATETIME_IMMUTABLE,
            self::ENCRYPTED_JSON,
            self::ENCRYPTED_STRING,
            self::ENCRYPTED_TEXT,
        ];
    }

    private function __construct()
    {
    }
}

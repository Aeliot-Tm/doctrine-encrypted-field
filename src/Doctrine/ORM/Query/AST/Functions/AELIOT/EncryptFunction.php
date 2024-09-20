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

namespace Aeliot\Bundle\DoctrineEncryptedField\Doctrine\ORM\Query\AST\Functions\AELIOT;

use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;

/**
 * "APP_ENCRYPT" "(" SimpleArithmeticExpression ")".
 */
final class EncryptFunction extends AbstractSingleArgumentFunction
{
    protected const FUNCTION_NAME = FunctionEnum::ENCRYPT;
}

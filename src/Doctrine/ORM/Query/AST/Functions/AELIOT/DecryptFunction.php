<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Doctrine\ORM\Query\AST\Functions\AELIOT;

use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;

/**
 * "APP_DECRYPT" "(" SimpleArithmeticExpression ")".
 */
final class DecryptFunction extends AbstractSingleArgumentFunction
{
    protected const FUNCTION_NAME = FunctionEnum::DECRYPT;
}

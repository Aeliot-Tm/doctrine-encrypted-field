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

use Aeliot\Bundle\DoctrineEncryptedField\Exception\ConfigurationException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\SimpleArithmeticExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

abstract class AbstractSingleArgumentFunction extends FunctionNode
{
    protected const FUNCTION_NAME = '';

    public ?SimpleArithmeticExpression $simpleArithmeticExpression = null;

    public function __construct(string $name)
    {
        if (!static::FUNCTION_NAME) {
            throw new ConfigurationException(sprintf('"%s::FUNCTION_NAME" constant was not defined.', static::class));
        }

        parent::__construct($name);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            '%s(%s)',
            static::FUNCTION_NAME,
            $sqlWalker->walkSimpleArithmeticExpression($this->simpleArithmeticExpression)
        );
    }

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->simpleArithmeticExpression = $parser->SimpleArithmeticExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}

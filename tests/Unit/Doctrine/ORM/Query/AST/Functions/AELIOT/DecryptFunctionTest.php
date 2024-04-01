<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Tests\Unit\Doctrine\ORM\Query\AST\Functions\AELIOT;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\ORM\Query\AST\Functions\AELIOT\DecryptFunction;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;
use Doctrine\ORM\Query\AST\SimpleArithmeticExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use PHPUnit\Framework\TestCase;

final class DecryptFunctionTest extends TestCase
{
    public function testParse(): void
    {
        $function = new DecryptFunction(FunctionEnum::DECRYPT);

        $simpleArithmeticExpression = $this->createMock(SimpleArithmeticExpression::class);

        $parser = $this->createMock(Parser::class);
        $parser->method('SimpleArithmeticExpression')->willReturn($simpleArithmeticExpression);

        $parser->expects($this->exactly(3))
            ->method('match')
            ->withConsecutive(
                [Lexer::T_IDENTIFIER],
                [Lexer::T_OPEN_PARENTHESIS],
                [Lexer::T_CLOSE_PARENTHESIS],
            );

        $function->parse($parser);

        self::assertEquals($simpleArithmeticExpression, $function->simpleArithmeticExpression);
    }

    public function testGetSQL(): void
    {
        $sqlWalker = $this->createMock(SqlWalker::class);
        $sqlWalker->method('walkSimpleArithmeticExpression')->willReturn('expression');

        $function = new DecryptFunction(FunctionEnum::DECRYPT);
        self::assertEquals(
            sprintf('%s(%s)', FunctionEnum::DECRYPT, 'expression'),
            $function->getSql($sqlWalker)
        );
    }
}

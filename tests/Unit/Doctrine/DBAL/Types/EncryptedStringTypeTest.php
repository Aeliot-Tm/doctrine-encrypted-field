<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Tests\Unit\Doctrine\DBAL\Types;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedStringType;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FieldTypeEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\PlatformEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EncryptedStringTypeTest extends TestCase
{
    public function testCanRequireSQLConversion(): void
    {
        $encryptedType = new EncryptedStringType();
        self::assertTrue($encryptedType->canRequireSQLConversion());
    }

    public function testConvertToDatabaseValueSQL(): void
    {
        $platform = $this->mockAbstractPlatform();

        $encryptedType = new EncryptedStringType();
        self::assertEquals(
            sprintf('%s(sqlExpr)', FunctionEnum::ENCRYPT),
            $encryptedType->convertToDatabaseValueSQL('sqlExpr', $platform)
        );
    }

    public function testConvertToPHPValueSQL(): void
    {
        $platform = $this->mockAbstractPlatform();

        $encryptedType = new EncryptedStringType();
        self::assertEquals(
            sprintf('%s(sqlExpr)', FunctionEnum::DECRYPT),
            $encryptedType->convertToPHPValueSQL('sqlExpr', $platform)
        );
    }

    public function testGetDefaultFieldLength(): void
    {
        $platform = $this->mockAbstractPlatform();

        $encryptedType = new EncryptedStringType();
        self::assertEquals(255, $encryptedType->getDefaultFieldLength($platform));
    }

    public function testGetName(): void
    {
        $encryptedType = new EncryptedStringType();

        self::assertEquals(FieldTypeEnum::ENCRYPTED_STRING, $encryptedType->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $platform = $this->mockAbstractPlatform();

        $encryptedType = new EncryptedStringType();
        $sqlDeclaration = $encryptedType->getSQLDeclaration([], $platform);
        self::assertEquals('BINARY_TYPE_DECLARATION', $sqlDeclaration);
    }

    private function mockAbstractPlatform(): AbstractPlatform&MockObject
    {
        $platform = $this->createMock(AbstractPlatform::class);
        $platform->method('getBinaryTypeDeclarationSQL')->willReturn('BINARY_TYPE_DECLARATION');
        $platform->method('getBlobTypeDeclarationSQL')->willReturn('BLOB_TYPE_DECLARATION');
        $platform->method('getName')->willReturn(PlatformEnum::MYSQL);

        return $platform;
    }
}

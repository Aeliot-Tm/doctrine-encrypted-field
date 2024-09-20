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

namespace Aeliot\Bundle\DoctrineEncryptedField\Tests\Unit\Doctrine\DBAL\Types;

use Aeliot\Bundle\DoctrineEncryptedField\Enum\PlatformEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

trait MockPlatformTrait
{
    private function mockPlatform(TestCase $test): AbstractPlatform&MockObject
    {
        $platform = $test->createMock(AbstractPlatform::class);
        $platform->method('getBinaryTypeDeclarationSQL')->willReturn('BINARY_TYPE_DECLARATION');
        $platform->method('getBlobTypeDeclarationSQL')->willReturn('BLOB_TYPE_DECLARATION');
        $platform->method('getName')->willReturn(PlatformEnum::MYSQL);

        return $platform;
    }
}

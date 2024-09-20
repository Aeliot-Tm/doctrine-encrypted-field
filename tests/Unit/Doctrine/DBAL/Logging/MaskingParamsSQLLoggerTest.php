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

namespace Aeliot\Bundle\DoctrineEncryptedField\Tests\Unit\Doctrine\DBAL\Logging;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Logging\MaskingParamsSQLLogger;
use Doctrine\DBAL\Logging\SQLLogger;
use PHPUnit\Framework\TestCase;

final class MaskingParamsSQLLoggerTest extends TestCase
{
    public function testCalledStopQuery(): void
    {
        $sqlLogger = $this->createMock(SQLLogger::class);
        $sqlLogger->expects($this->once())->method('stopQuery');
        $maskingParamsSQLLogger = new MaskingParamsSQLLogger($sqlLogger, []);

        $maskingParamsSQLLogger->stopQuery();
    }

    public function testParamsMaskingOnStartQuery(): void
    {
        $sql = 'sql';
        $params = [
            'maskedParam' => 'maskedParamValue',
            'unmaskedParam' => 'unmaskedParamValue',
        ];
        $types = [];

        $sqlLogger = $this->createMock(SQLLogger::class);
        $sqlLogger
            ->expects($this->once())
            ->method('startQuery')
            ->with(
                $sql,
                array_merge($params, ['maskedParam' => '****************']),
                $types
            );

        $maskingParamsSQLLogger = new MaskingParamsSQLLogger($sqlLogger, ['maskedParam']);

        $maskingParamsSQLLogger->startQuery($sql, $params, $types);
    }
}

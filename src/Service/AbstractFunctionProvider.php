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

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Aeliot\Bundle\DoctrineEncryptedField\Exception\ConfigurationException;
use Doctrine\DBAL\Connection;

abstract class AbstractFunctionProvider implements FunctionProviderInterface
{
    public function getDefinition(string $functionName, Connection $connection): string
    {
        $definitions = $this->getDefinitions($connection);
        $platformName = $connection->getDatabasePlatform()->getName();

        if (!isset($definitions[$functionName][$platformName])) {
            throw new ConfigurationException(
                sprintf('Undefined function "%s" for platform "%s".', $functionName, $platformName)
            );
        }

        return $definitions[$functionName][$platformName];
    }

    abstract public function getDefinitions(Connection $connection): array;

    public function getNames(Connection $connection): array
    {
        return array_keys($this->getDefinitions($connection));
    }
}

<?php

declare(strict_types=1);

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

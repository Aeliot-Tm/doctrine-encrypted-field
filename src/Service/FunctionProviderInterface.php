<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Doctrine\DBAL\Connection;

interface FunctionProviderInterface
{
    public function getDefinition(string $functionName, Connection $connection): string;

    /**
     * @return array<string,array<string,string>>
     */
    public function getDefinitions(Connection $connection): array;

    /**
     * @return string[]
     */
    public function getNames(Connection $connection): array;
}

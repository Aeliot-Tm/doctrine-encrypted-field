<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

interface SecretProviderInterface
{
    public function getKey(string $connectionName): string;

    public function getSecret(string $connectionName): string;
}

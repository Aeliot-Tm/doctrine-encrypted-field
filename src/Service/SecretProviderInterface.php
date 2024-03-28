<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

interface SecretProviderInterface
{
    public function getSecret(string $connectionName): string;
}

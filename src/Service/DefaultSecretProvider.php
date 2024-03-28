<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

final class DefaultSecretProvider implements SecretProviderInterface
{
    public function getSecret(string $connectionName): string
    {
        return (string) getenv('DB_ENCRYPTION_KEY');
    }
}

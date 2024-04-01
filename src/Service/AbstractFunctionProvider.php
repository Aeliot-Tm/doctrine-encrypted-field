<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Aeliot\Bundle\DoctrineEncryptedField\Enum\DatabaseErrorEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\ParameterEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\PlatformEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Exception\ConfigurationException;
use Doctrine\DBAL\Connection;

abstract class AbstractFunctionProvider implements FunctionProviderInterface
{
    public function getList(): array
    {
        return array_keys($this->getDefinitions());
    }

    public function getDefinition(string $functionName, Connection $connection): string
    {
        $definitions = $this->getDefinitions();
        $platformName = $connection->getDatabasePlatform()->getName();

        if (!isset($definitions[$functionName][$platformName])) {
            throw new ConfigurationException(
                sprintf('Undefined function "%s" for platform "%s".', $functionName, $platformName)
            );
        }

        return $definitions[$functionName][$platformName];
    }

    /**
     * @return array<string,array<string,string>>
     */
    protected function getDefinitions(): array
    {
        return [
            FunctionEnum::DECRYPT => [
                PlatformEnum::MYSQL => sprintf(
                    'CREATE FUNCTION %1$s(source_data LONGBLOB) RETURNS LONGTEXT DETERMINISTIC
                        BEGIN
                            RETURN AES_DECRYPT(source_data, %2$s());
                        END;',
                    FunctionEnum::DECRYPT,
                    FunctionEnum::GET_ENCRYPTION_KEY
                ),
            ],
            FunctionEnum::ENCRYPT => [
                PlatformEnum::MYSQL => sprintf(
                    'CREATE FUNCTION %1$s(source_data LONGTEXT) RETURNS LONGBLOB DETERMINISTIC
                        BEGIN
                            RETURN AES_ENCRYPT(source_data, %2$s());
                        END;',
                    FunctionEnum::ENCRYPT,
                    FunctionEnum::GET_ENCRYPTION_KEY
                ),
            ],
            FunctionEnum::GET_ENCRYPTION_KEY => [
                PlatformEnum::MYSQL => sprintf(
                    'CREATE FUNCTION %1$s() RETURNS TEXT DETERMINISTIC
                        BEGIN
                            IF (@%2$s IS NULL OR LENGTH(@%2$s) = 0) THEN
                                SIGNAL SQLSTATE \'%3$s\'
                                    SET MESSAGE_TEXT = \'Encryption key not found\';
                            END IF;
                            RETURN @%2$s;
                        END;',
                    FunctionEnum::GET_ENCRYPTION_KEY,
                    ParameterEnum::ENCRYPTION_KEY,
                    DatabaseErrorEnum::EMPTY_ENCRYPTION_KEY
                ),
            ],
        ];
    }
}

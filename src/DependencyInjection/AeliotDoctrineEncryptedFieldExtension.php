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

namespace Aeliot\Bundle\DoctrineEncryptedField\DependencyInjection;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedDateImmutableType;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedDateTimeImmutableType;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedDateTimeType;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedDateType;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedJsonType;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedStringType;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedTextType;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\ORM\Query\AST\Functions\AELIOT\DecryptFunction;
use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\ORM\Query\AST\Functions\AELIOT\EncryptFunction;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FieldTypeEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Service\ConnectionPreparerInterface;
use Aeliot\Bundle\DoctrineEncryptedField\Service\EncryptionAvailabilityCheckerInterface;
use Aeliot\Bundle\DoctrineEncryptedField\Service\FunctionProviderInterface;
use Aeliot\Bundle\DoctrineEncryptedField\Service\SecretProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class AeliotDoctrineEncryptedFieldExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'aeliot.doctrine_encrypted_field.encrypted_connections',
            $config['encrypted_connections'],
        );

        $loader = new YamlFileLoader($container, new FileLocator(sprintf('%s/../../config', __DIR__)));
        $loader->load('services.yaml');

        $container->setAlias(ConnectionPreparerInterface::class, new Alias($config['connection_preparer']));
        $container->setAlias(
            EncryptionAvailabilityCheckerInterface::class,
            new Alias($config['encryption_availability_checker']),
        );
        $container->setAlias(FunctionProviderInterface::class, new Alias($config['functions_provider']));
        $container->setAlias(SecretProviderInterface::class, new Alias($config['secret_provider']));
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineConfig($container);
    }

    private function prependDoctrineConfig(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig(
            'doctrine',
            [
                'dbal' => [
                    'types' => [
                        FieldTypeEnum::ENCRYPTED_DATE => EncryptedDateType::class,
                        FieldTypeEnum::ENCRYPTED_DATE_IMMUTABLE => EncryptedDateImmutableType::class,
                        FieldTypeEnum::ENCRYPTED_DATETIME => EncryptedDateTimeType::class,
                        FieldTypeEnum::ENCRYPTED_DATETIME_IMMUTABLE => EncryptedDateTimeImmutableType::class,
                        FieldTypeEnum::ENCRYPTED_JSON => EncryptedJsonType::class,
                        FieldTypeEnum::ENCRYPTED_STRING => EncryptedStringType::class,
                        FieldTypeEnum::ENCRYPTED_TEXT => EncryptedTextType::class,
                    ],
                ],
                'orm' => [
                    'dql' => [
                        'string_functions' => [
                            FunctionEnum::DECRYPT => DecryptFunction::class,
                            FunctionEnum::ENCRYPT => EncryptFunction::class,
                        ],
                    ],
                ],
            ]
        );
    }
}

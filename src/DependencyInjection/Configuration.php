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

use Aeliot\Bundle\DoctrineEncryptedField\Service\DefaultConnectionPreparer;
use Aeliot\Bundle\DoctrineEncryptedField\Service\DefaultEncryptionAvailabilityChecker;
use Aeliot\Bundle\DoctrineEncryptedField\Service\DefaultFunctionProvider;
use Aeliot\Bundle\DoctrineEncryptedField\Service\DefaultSecretProvider;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('aeliot_doctrine_encrypted_field');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootChildren = $rootNode->children();

        $this->configConnectionPreparer($rootChildren);
        $this->configEncryptedConnections($rootChildren);
        $this->configEncryptionAvailabilityChecker($rootChildren);
        $this->configFunctionProvider($rootChildren);
        $this->configSecretProvider($rootChildren);

        return $treeBuilder;
    }

    private function configConnectionPreparer(NodeBuilder $rootChildren): void
    {
        $rootChildren
            ->scalarNode('connection_preparer')
            ->cannotBeEmpty()
            ->defaultValue(DefaultConnectionPreparer::class);
    }

    private function configEncryptionAvailabilityChecker(NodeBuilder $rootChildren): void
    {
        $rootChildren
            ->scalarNode('encryption_availability_checker')
            ->cannotBeEmpty()
            ->defaultValue(DefaultEncryptionAvailabilityChecker::class);
    }

    private function configFunctionProvider(NodeBuilder $rootChildren): void
    {
        $rootChildren
            ->scalarNode('functions_provider')
            ->cannotBeEmpty()
            ->defaultValue(DefaultFunctionProvider::class);
    }

    private function configSecretProvider(NodeBuilder $rootChildren): void
    {
        $rootChildren
            ->scalarNode('secret_provider')
            ->cannotBeEmpty()
            ->defaultValue(DefaultSecretProvider::class);
    }

    private function configEncryptedConnections(NodeBuilder $rootChildren): void
    {
        $encryptionConnectionsNode = $rootChildren->arrayNode('encrypted_connections');
        $encryptionConnectionsNode->beforeNormalization()->ifEmpty()->thenEmptyArray();
        $encryptionConnectionsNode
            ->beforeNormalization()
            ->ifString()
            ->then(static fn (string $value): array => [$value]);
        $encryptionConnectionsNode->scalarPrototype();
        $encryptionConnectionsNode->defaultValue(['default']);
    }
}

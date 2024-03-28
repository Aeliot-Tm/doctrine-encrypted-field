<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\DependencyInjection\Compiler;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\ORM\Query\EncryptionSQLWalker;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class EncryptionSQLWalkerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $connections = $container->getParameter('aeliot.doctrine_encrypted_field.encrypted_connections');
        foreach ($connections as $connection) {
            $definition = $container->getDefinition(sprintf('doctrine.orm.%s_configuration', $connection));
            $definition->addMethodCall(
                'setDefaultQueryHint',
                [Query::HINT_CUSTOM_OUTPUT_WALKER, EncryptionSQLWalker::class],
            );
        }
    }
}

<?php

/*
 * This file is part of the Doctrine Encrypted Field Bundle.
 *
 * (c) Anatoliy Melnikov <5785276@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Aeliot\Bundle\DoctrineEncryptedField;

use Aeliot\Bundle\DoctrineEncryptedField\DependencyInjection\Compiler\EncryptionSQLWalkerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AeliotDoctrineEncryptedFieldBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new EncryptionSQLWalkerCompilerPass());
    }
}

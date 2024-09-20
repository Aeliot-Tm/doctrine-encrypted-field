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

namespace Aeliot\Bundle\DoctrineEncryptedField\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'doctrine-encrypted-field:functions:uninstall')]
final class FunctionsUninstallCommand extends FunctionsInstallationCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Uninstall functions');
    }

    protected function prepare(Connection $connection, string $functionName, OutputInterface $output): void
    {
        if ($this->functionManager->hasFunction($connection, $functionName)) {
            $this->functionManager->removeFunction($connection, $functionName, $output);
        }
    }
}

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

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'doctrine-encrypted-field:database:decrypt')]
final class DatabaseDecryptCommand extends DatabaseTransformCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Decrypt database');
    }

    protected function transform(string $managerName, OutputInterface $output): void
    {
        $this->encryptionService->decrypt($managerName, $output);
    }
}

<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'doctrine-encrypted-field:database:encrypt')]
final class DatabaseEncryptCommand extends DatabaseTransformCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Encrypt database');
    }

    protected function transform(string $managerName, OutputInterface $output): void
    {
        $this->encryptionService->encrypt($managerName, $output);
    }
}

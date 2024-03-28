<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Command;

use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'doctrine-encrypted-field:fields:encrypt')]
final class FieldsEncryptCommand extends FieldsTransformCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Encrypt fields');
    }

    protected function getFunction(): string
    {
        return FunctionEnum::ENCRYPT;
    }
}

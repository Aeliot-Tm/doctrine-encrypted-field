<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Command;

use Aeliot\Bundle\DoctrineEncryptedField\Service\TableEncryptor;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class FieldsTransformCommand extends Command
{
    public function __construct(
        private ConnectionRegistry $registry,
        private TableEncryptor $tableEncryptor,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('connection', InputArgument::REQUIRED, 'Connection name');
        $this->addOption(
            'fields',
            null,
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            'Table fields to be transformed. Example: --fields="table_1:field_1,field_2,field_3"'
        );
        $this->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Dump sql instead of its execution');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $anOutput = $input->getOption('dump-sql') ? $output : new NullOutput();
        /** @var Connection $connection */
        $connection = $this->registry->getConnection($input->getArgument('connection'));
        $tableFields = $input->getOption('fields');
        $function = $this->getFunction();

        foreach ($tableFields as $option) {
            [$table, $fieldsList] = explode(':', $option, 2);
            $fields = explode(',', $fieldsList);
            $this->tableEncryptor->convert($connection, $table, $fields, $function, $anOutput);
        }

        return self::SUCCESS;
    }

    abstract protected function getFunction(): string;
}

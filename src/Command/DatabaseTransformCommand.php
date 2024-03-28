<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Command;

use Aeliot\Bundle\DoctrineEncryptedField\Service\DatabaseEncryptionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class DatabaseTransformCommand extends Command
{
    public function __construct(protected DatabaseEncryptionService $encryptionService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('manager', InputArgument::REQUIRED, 'Entity manager name');
        $this->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Dump sql instead of its execution');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $anOutput = $input->getOption('dump-sql') ? $output : new NullOutput();
        $this->transform($input->getArgument('manager'), $anOutput);

        return self::SUCCESS;
    }

    abstract protected function transform(string $managerName, OutputInterface $output): void;
}

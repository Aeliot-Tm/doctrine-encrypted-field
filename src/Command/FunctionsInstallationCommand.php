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

use Aeliot\Bundle\DoctrineEncryptedField\Service\FunctionManager;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class FunctionsInstallationCommand extends Command
{
    /**
     * @param string[] $encryptedConnections
     */
    public function __construct(
        private array $encryptedConnections,
        protected FunctionManager $functionManager,
        private ConnectionRegistry $registry,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('connection', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Connection name');
        $this->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Dump sql instead of its execution');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connectionNames = $this->getConnectionNames($input);
        $anOutput = $input->getOption('dump-sql') ? $output : new NullOutput();

        foreach ($connectionNames as $connectionName) {
            /** @var Connection $connection */
            $connection = $this->registry->getConnection($connectionName);

            foreach ($this->functionManager->getNames($connection) as $functionName) {
                $this->prepare($connection, $functionName, $anOutput);
            }
        }

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    protected function getConnectionNames(InputInterface $input): array
    {
        if ($names = $input->getArgument('connection')) {
            // TODO: check if required connections are configured as encrypted
            //       or default when list of encrypted connections is empty.
            return $names;
        }

        // TODO: use only name of default connection instead of list of all connections
        return $this->encryptedConnections ?: $this->registry->getConnectionNames();
    }

    abstract protected function prepare(Connection $connection, string $functionName, OutputInterface $output): void;
}

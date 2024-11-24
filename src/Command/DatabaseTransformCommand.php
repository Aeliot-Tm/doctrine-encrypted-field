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

use Aeliot\Bundle\DoctrineEncryptedField\Service\DatabaseEncryptionService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class DatabaseTransformCommand extends Command
{
    public function __construct(
        private ManagerRegistry $registry,
        protected DatabaseEncryptionService $encryptionService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        /* TODO: process list of entity managers
         *       1. accept array of entity managers
         *       2. use list of entity managers by list of encrypted connections (`...encrypted_connections`)
         *          as default value
         */
        $this->addArgument('manager', InputArgument::OPTIONAL, 'Entity manager name');
        $this->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Dump sql instead of its execution');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $anOutput = $input->getOption('dump-sql') ? $output : new NullOutput();
        $managerName = $this->getEntityManagerName($input);
        $this->transform($managerName, $anOutput);

        return self::SUCCESS;
    }

    abstract protected function transform(string $managerName, OutputInterface $output): void;

    private function getEntityManagerName(InputInterface $input): string
    {
        $managerName = $input->getArgument('manager');
        if (!$managerName) {
            if (1 < \count($this->registry->getManagers())) {
                throw new \DomainException('Option "manager" is required when configured more then one');
            }
            $managerName = $this->registry->getDefaultManagerName();
        }

        return $managerName;
    }
}

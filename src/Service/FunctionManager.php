<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class FunctionManager
{
    public function __construct(private FunctionProviderInterface $functionProvider)
    {
    }

    public function addFunction(Connection $connection, string $functionName, ?OutputInterface $output): void
    {
        $sql = $this->functionProvider->getDefinition($functionName, $connection);
        $this->executeOrShow($connection, $sql, $output);
    }

    /**
     * @return string[]
     */
    public function getList(): array
    {
        return $this->functionProvider->getList();
    }

    public function hasFunction(Connection $connection, string $functionName): bool
    {
        $sql = 'SELECT COUNT(1) AS functions_count
                  FROM information_schema.ROUTINES
                 WHERE ROUTINE_SCHEMA = :routine_schema
                   AND ROUTINE_TYPE = :routine_type
                   AND ROUTINE_NAME = :routine_name';

        return (bool) $connection->prepare($sql)
            ->executeQuery([
                'routine_schema' => $connection->getDatabase(),
                'routine_type' => 'FUNCTION',
                'routine_name' => $functionName,
            ])
            ->fetchAssociative()['functions_count'];
    }

    public function removeFunction(Connection $connection, string $functionName, ?OutputInterface $output): void
    {
        $sql = sprintf('DROP FUNCTION %s;', $functionName);
        $this->executeOrShow($connection, $sql, $output);
    }

    private function executeOrShow(Connection $connection, string $sql, ?OutputInterface $output): void
    {
        if ($output && !$output instanceof NullOutput) {
            $output->writeln($sql);
        } else {
            $connection->prepare($sql)->executeStatement();
        }
    }
}

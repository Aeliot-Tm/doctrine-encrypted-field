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

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Aeliot\Bundle\DoctrineEncryptedField\Enum\FieldTypeEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FunctionEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Exception\EncryptionAvailabilityException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Symfony\Component\Console\Output\OutputInterface;

final class DatabaseEncryptionService
{
    public function __construct(
        private EncryptionAvailabilityCheckerInterface $databaseEncryptionChecker,
        private ManagerRegistry $registry,
        private TableEncryptor $tableEncryptor,
    ) {
    }

    public function decrypt(string $managerName, OutputInterface $output): void
    {
        $this->convertDatabases($managerName, FunctionEnum::DECRYPT, $output);
    }

    public function encrypt(string $managerName, OutputInterface $output): void
    {
        $this->convertDatabases($managerName, FunctionEnum::ENCRYPT, $output);
    }

    private function convertDatabases(string $managerName, string $function, OutputInterface $output): void
    {
        /** @var EntityManager $manager */
        $manager = $this->registry->getManager($managerName);

        if (!$this->databaseEncryptionChecker->isEncryptionAvailable($manager, FunctionEnum::ENCRYPT === $function)) {
            throw new EncryptionAvailabilityException(
                sprintf('Connection "%s" can not be converted.', $managerName)
            );
        }

        try {
            $manager->beginTransaction();

            /** @var ClassMetadataInfo<object> $metadata */
            foreach ($manager->getMetadataFactory()->getAllMetadata() as $metadata) {
                $fields = $this->getFields($metadata);
                $tableName = $metadata->getTableName();
                $output->writeln(json_encode([$tableName => $fields], \JSON_THROW_ON_ERROR));
                if (!$fields) {
                    continue;
                }

                $columns = $this->getColumns($metadata, $fields);
                $this->tableEncryptor->convert($manager->getConnection(), $tableName, $columns, $function, $output);
            }

            $manager->commit();
        } catch (\Throwable $exception) {
            $manager->rollback();

            throw $exception;
        }
    }

    /**
     * @param ClassMetadataInfo<object> $metadata
     * @param array<array-key,string> $fields
     *
     * @return array<array-key,string>
     */
    private function getColumns(ClassMetadataInfo $metadata, array $fields): array
    {
        return array_map(static fn (string $fieldName) => $metadata->getColumnName($fieldName), $fields);
    }

    /**
     * @param ClassMetadataInfo<object> $metadata
     *
     * @return string[]
     */
    private function getFields(ClassMetadata $metadata): array
    {
        $fieldsToEncrypt = [];

        foreach ($metadata->getFieldNames() as $fieldName) {
            $fieldType = $metadata->getTypeOfField($fieldName);
            if (\in_array($fieldType, FieldTypeEnum::all(), true)) {
                $fieldsToEncrypt[] = $fieldName;
            }
        }

        return $fieldsToEncrypt;
    }
}

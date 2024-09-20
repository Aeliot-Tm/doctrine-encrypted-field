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

namespace Aeliot\Bundle\DoctrineEncryptedField\EventListener;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Types\EncryptedFieldLengthInterface;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\FieldTypeEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Exception\ConfigurationException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

final class LoadClassMetadataListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        /** @var AbstractPlatform $platform */
        $platform = $eventArgs->getEntityManager()->getConnection()->getDatabasePlatform();
        $this->updateFieldMappings($eventArgs->getClassMetadata(), $platform);
    }

    /**
     * @param ClassMetadata<object> $classMetadata
     */
    private function updateFieldMappings(ClassMetadata $classMetadata, AbstractPlatform $platform): void
    {
        $encryptedTypes = FieldTypeEnum::all();

        foreach ($classMetadata->fieldMappings as &$fieldMapping) {
            $fieldType = $fieldMapping['type'];
            if (!\in_array($fieldType, $encryptedTypes, true)) {
                continue;
            }

            $length = $fieldMapping['length']
                ?? $this->getFieldTypeDefinition($fieldType)->getDefaultFieldLength($platform);

            /* @link https://dev.mysql.com/doc/refman/5.7/en/encryption-functions.html#function_aes-encrypt */
            $fieldMapping['length'] = null === $length ? null : 16 * (floor($length * 4 / 16) + 1);
        }
    }

    private function getFieldTypeDefinition(string $fieldType): EncryptedFieldLengthInterface
    {
        $fieldTypeDefinition = Type::getType($fieldType);
        if (!$fieldTypeDefinition instanceof EncryptedFieldLengthInterface) {
            throw new ConfigurationException(
                sprintf('Type "%s" should implement interface "%s".', $fieldType, EncryptedFieldLengthInterface::class)
            );
        }

        return $fieldTypeDefinition;
    }
}

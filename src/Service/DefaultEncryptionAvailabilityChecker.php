<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Doctrine\ORM\EntityManager;

final class DefaultEncryptionAvailabilityChecker implements EncryptionAvailabilityCheckerInterface
{
    public function isEncryptionAvailable(EntityManager $manager, bool $isGoingEncrypt): bool
    {
        return !empty(getenv('DB_ENCRYPTION_KEY'));
    }
}

<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Service;

use Doctrine\ORM\EntityManager;

interface EncryptionAvailabilityCheckerInterface
{
    public function isEncryptionAvailable(EntityManager $manager, bool $isGoingEncrypt): bool;
}

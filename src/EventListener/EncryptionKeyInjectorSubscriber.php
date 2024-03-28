<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\EventListener;

use Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Logging\MaskingParamsSQLLogger;
use Aeliot\Bundle\DoctrineEncryptedField\Enum\ParameterEnum;
use Aeliot\Bundle\DoctrineEncryptedField\Exception\ConnectionException;
use Aeliot\Bundle\DoctrineEncryptedField\Exception\SecurityConfigurationException;
use Aeliot\Bundle\DoctrineEncryptedField\Service\ConnectionPreparerInterface;
use Aeliot\Bundle\DoctrineEncryptedField\Service\SecretProviderInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\Persistence\ConnectionRegistry;

final class EncryptionKeyInjectorSubscriber implements EventSubscriber
{
    private const ENCRYPTION_KEY_PARAMETER = 'app_encryption_key';

    /**
     * @param string[] $encryptedConnections
     */
    public function __construct(
        private array $encryptedConnections,
        private ConnectionRegistry $registry,
        private ConnectionPreparerInterface $connectionPreparer,
        private SecretProviderInterface $secretProvider,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postConnect,
        ];
    }

    public function postConnect(ConnectionEventArgs $event): void
    {
        $currentConnection = $event->getConnection();
        $connectionName = $this->getConnectionName($currentConnection);
        if (\in_array($connectionName, $this->encryptedConnections, true)) {
            $key = $this->secretProvider->getSecret($connectionName);
            if (!$key) {
                throw new SecurityConfigurationException('Project encryption key is undefined.');
            }

            $this->maskParamsLogging($currentConnection);
            $this->prepareConnection($currentConnection, $key);
        }
    }

    private function getConnectionName(Connection $currentConnection): ?string
    {
        foreach ($this->registry->getConnections() as $name => $connection) {
            if ($connection === $currentConnection) {
                return $name;
            }
        }

        return null;
    }

    private function maskParamsLogging(Connection $currentConnection): void
    {
        if ($logger = $currentConnection->getConfiguration()->getSQLLogger()) {
            $currentConnection->getConfiguration()->setSQLLogger(
                new MaskingParamsSQLLogger($logger, [self::ENCRYPTION_KEY_PARAMETER])
            );
        }
    }

    private function prepareConnection(Connection $currentConnection, #[\SensitiveParameter] string $key): void
    {
        $this->connectionPreparer->prepareConnection($currentConnection);
        $param = $this->connectionPreparer->wrapParameter(sprintf(':%s', self::ENCRYPTION_KEY_PARAMETER));
        $sql = sprintf('SET @%s = %s;', ParameterEnum::ENCRYPTION_KEY, $param);
        $statement = $currentConnection->prepare($sql);

        try {
            $statement->executeStatement([self::ENCRYPTION_KEY_PARAMETER => $key]);
        } catch (DBALException $exception) {
            throw new ConnectionException('Failed to inject encryption key.', 0, $exception);
        }
    }
}

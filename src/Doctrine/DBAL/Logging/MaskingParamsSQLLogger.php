<?php

declare(strict_types=1);

namespace Aeliot\Bundle\DoctrineEncryptedField\Doctrine\DBAL\Logging;

use Doctrine\DBAL\Logging\SQLLogger;

final class MaskingParamsSQLLogger implements SQLLogger
{
    /**
     * @param string[] $maskParams
     */
    public function __construct(private SQLLogger $decorated, private array $maskParams)
    {
    }

    public function startQuery($sql, ?array $params = null, ?array $types = null): void
    {
        if (\is_array($params)) {
            foreach ($this->maskParams as $maskParam) {
                if (\array_key_exists($maskParam, $params)) {
                    $params[$maskParam] = str_repeat('*', \strlen($params[$maskParam]));
                }
            }
        }
        $this->decorated->startQuery($sql, $params, $types);
    }

    public function stopQuery(): void
    {
        $this->decorated->stopQuery();
    }
}

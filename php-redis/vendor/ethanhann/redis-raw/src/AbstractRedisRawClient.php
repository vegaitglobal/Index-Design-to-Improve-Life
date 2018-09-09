<?php

namespace Ehann\RedisRaw;

use Ehann\RedisRaw\Exceptions\RawCommandErrorException;
use Ehann\RedisRaw\Exceptions\UnknownIndexNameException;
use Ehann\RedisRaw\Exceptions\UnknownRediSearchCommandException;
use Ehann\RedisRaw\Exceptions\UnsupportedRediSearchLanguageException;
use Ehann\RedisRaw\Exceptions\UnsupportedRedisDatabaseException;
use Exception;
use Psr\Log\LoggerInterface;

abstract class AbstractRedisRawClient implements RedisRawClientInterface
{
    const PREDIS_LIBRARY = 'Predis';
    const PHP_REDIS_LIBRARY = 'PhpRedis';
    const REDIS_CLIENT_LIBRARY = 'RedisClient';

    public $redis;
    /** @var  LoggerInterface */
    protected $logger;

    public function connect($hostname = '127.0.0.1', $port = 6379, $db = 0, $password = null): RedisRawClientInterface
    {
        return $this;
    }

    public function flushAll()
    {
        $this->redis->flushAll();
    }

    public function multi(bool $usePipeline = false)
    {
    }

    public function rawCommand(string $command, array $arguments)
    {
    }

    public function prepareRawCommandArguments(string $command, array $arguments) : array
    {
        foreach ($arguments as $index => $argument) {
            if (!is_scalar($arguments[$index])) {
                $arguments[$index] = (string)$argument;
            }
        }
        array_unshift($arguments, $command);
        if ($this->logger) {
            $this->logger->debug(implode(' ', $arguments));
        }
        return $arguments;
    }

    /**
     * @param $payload
     * @return mixed
     * @throws RawCommandErrorException
     * @throws UnknownIndexNameException
     * @throws UnknownRediSearchCommandException
     * @throws UnsupportedRediSearchLanguageException
     * @throws UnsupportedRedisDatabaseException
     */
    public function validateRawCommandResults($payload)
    {
        $isPayloadException = $payload instanceof Exception;
        $message = $isPayloadException ? $payload->getMessage() : $payload;

        if (!is_string($message)) {
            return;
        }
        $message = strtolower($message);
        if ($message === 'cannot create index on db != 0') {
            throw new UnsupportedRedisDatabaseException();
        }
        if ($message === 'unknown index name') {
            throw new UnknownIndexNameException();
        }
        if (in_array($message, ['unsupported language', 'unsupported stemmer language'])) {
            throw new UnsupportedRediSearchLanguageException();
        }
        if (strpos($message, 'err unknown command \'ft.') !== false) {
            throw new UnknownRediSearchCommandException($message);
        }
        if ($isPayloadException) {
            throw new RawCommandErrorException('', 0, $payload);
        }
    }

    public function normalizeRawCommandResult($rawResult)
    {
        return $rawResult === 'OK' ? true : $rawResult;
    }

    public function setLogger(LoggerInterface $logger): RedisRawClientInterface
    {
        $this->logger = $logger;
        return $this;
    }
}

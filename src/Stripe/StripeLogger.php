<?php

namespace App\Stripe;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;
use Stripe\Util\LoggerInterface as StripeLoggerInterface;

/**
 * A Decorator class that provides a stripe logger implementation based on a PSR-3 logger.
 */
final class StripeLogger implements StripeLoggerInterface, LoggerInterface
{
    use LoggerTrait;

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = [])
    {
        $this->logger->log($level, $message, $context);
    }
}
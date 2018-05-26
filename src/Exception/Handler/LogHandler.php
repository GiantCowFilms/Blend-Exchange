<?php declare(strict_types=1);

namespace BlendExchange\Exception\Handler;
use Whoops\Handler\Handler;
use Monolog\Logger;

final class LogHandler extends Handler
{
    private $logger;
    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    public function handle() 
    {
        $exception = $this->getException();

        $this->logger->error(sprintf(
            "%s: %s in file %s on line %d",
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));

        return Handler::DONE;
    }
}
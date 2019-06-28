<?php declare(strict_types=1);

namespace EdmondsCommerce\ConsoleErrorLogger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

//    services:
//    kernel.listener.command_dispatch:
//            class: EdmondsCommerce\ConsoleErrorLogger\ConsoleErrorListener
//            tags:
//                - { name: kernel.event_listener, event: console.error }
//                - { name: kernel.event_listener, event: console.terminate }

class ConsoleErrorListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handles logging any non-caught console errors
     *
     * @param ConsoleErrorEvent $event
     */
    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        $commandName = null === $event->getCommand() ? 'Unknown' : $event->getCommand()->getName();
        $error       = $event->getError();

        $message = sprintf(
            '%s: %s (uncaught exception) at %s line %s while running console command `%s`',
            get_class($error),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $commandName
        );

        $this->logger->critical(
            $message,
            [
                'error' => $error,
                'trace' => $error->getTraceAsString()
            ]
        );
    }

    /**
     * Handles logging any non-zero exit statuses
     *
     * @param ConsoleTerminateEvent $event
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $statusCode = $event->getExitCode();
        $commandName    = null === $event->getCommand() ? 'Unknown' : $event->getCommand()->getName();

        if ($statusCode === 0) {
            return;
        }

        $message = sprintf(
            "Command '%s' exited with status code %d",
            $commandName,
            $statusCode
        );

        $this->logger->critical($message);
    }
}
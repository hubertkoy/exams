<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;


/**
 * Class CronRunningEvent
 * Cron job: * * * * * bin/console messenger:consume async --memory-limit=128M (every minute)
 * @package App\Event
 */
class CronRunningEvent implements EventSubscriberInterface
{
    public function onWorkerRunning(WorkerRunningEvent $event)
    {
        if ($event->isWorkerIdle()) {
            $event->getWorker()->stop();
        }
    }

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents()
    {
        return [
            WorkerRunningEvent::class => 'onWorkerRunning'
        ];
    }
}

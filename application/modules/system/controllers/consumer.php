<?php
/**
 * Generated controller
 *
 * @author   dev
 * @created  2019-01-14 18:41:04
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Config;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

/**
 * @method CLI
 *
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */

    /** @var \Interop\Queue\ConnectionFactory $driver */
    $driver = Config::get('queue', 'providers', 'redis')();
    $context = $driver->createContext();
    $queue = $context->createQueue('bluz:queue:messages');


    $consumer = $context->createConsumer($queue);

    while (true) {
        $message = $consumer->receive();

        // process a message
        if (random_int(0, 1)) {
            $consumer->acknowledge($message);
            echo("Message was acknowledged\n");
        } else {
            $consumer->reject($message);
            echo("Message was rejected\n");
        }
    }
};

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
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

/**
 * @privilege Info
 *
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::title('Queues');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            Layout::ahref('System', ['system', 'index']),
            __('Queues'),
        ]
    );

    /** @var \Interop\Queue\ConnectionFactory $driver */
    $driver = Config::get('queue', 'providers', 'redis')();
    $context = $driver->createContext();
    $queue = $context->createQueue('bluz:queue:messages');

    if (Request::getQuery('produce')) {
        $message = $context->createMessage(
            'Message',
            [
                'title' => 'Hello!',
                'body' => 'How are you?'
            ]
        );

        $producer = $context->createProducer();

        $producer->send($queue, $message);

        Messages::addNotice('Message was created');
    }

    if (Request::getQuery('consume')) {
        $consumer = $context->createConsumer($queue);

        $message = $consumer->receive();

        // process a message
        if (random_int(0, 1)) {
            $consumer->acknowledge($message);
            Messages::addSuccess('Message was acknowledged');
        } else {
            $consumer->reject($message);
            Messages::addError('Message was rejected');
        }
    }
};

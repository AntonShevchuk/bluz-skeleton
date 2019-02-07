<?php
/**
 * Queue configuration
 *
 * @link https://github.com/bluzphp/framework/wiki/Queue
 * @return array
 */
return [
    'enabled' => true,
    'adapter' => 'redis',
    'providers' => [
        'redis' => function() {
            // connect to Redis at example.com port 1000 using phpredis extension
            $factory = new \Enqueue\Redis\RedisConnectionFactory([
                'scheme' => 'redis',
                'scheme_extensions' => ['phpredis'],
                'host' => '127.0.0.1',
                'port' => 6379
            ]);
            return $factory;
        },
    ]
];

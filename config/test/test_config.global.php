<?php
declare(strict_types=1);

namespace ShlinkioTest\Shlink;

use GuzzleHttp\Client;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ServiceManager\Factory\InvokableFactory;
use function realpath;
use function sys_get_temp_dir;

return [

    'debug' => true,
    ConfigAggregator::ENABLE_CACHE => false,

    'url_shortener' => [
        'domain' => [
            'schema' => 'http',
            'hostname' => 'doma.in',
        ],
    ],

    'zend-expressive-swoole' => [
        'swoole-http-server' => [
            'port' => 9999,
            'host' => '127.0.0.1',
            'process-name' => 'shlink_test',
            'options' => [
                'pid_file' => sys_get_temp_dir() . '/shlink-test-swoole.pid',
            ],
        ],
    ],

    'dependencies' => [
        'factories' => [
            Common\TestHelper::class => InvokableFactory::class,
            'shlink_test_api_client' => function () {
                return new Client(['base_uri' => 'http://localhost:9999/']);
            },
        ],
    ],

    'entity_manager' => [
        'connection' => [
            'driver' => 'pdo_sqlite',
            'path' => realpath(sys_get_temp_dir()) . '/shlink-tests.db',
        ],
    ],

    'data_fixtures' => [
        'paths' => [
            __DIR__ . '/../../module/Rest/test-api/Fixtures',
        ],
    ],

];

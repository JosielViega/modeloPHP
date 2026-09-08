<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require __DIR__ . '/lib/Port.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

$host = '127.0.0.1';
$port = filter_var(env('APP_PORT'), FILTER_VALIDATE_INT);

if ($port === false || !TemplateTools\Port::isValid($port)) {
    fwrite(STDERR, "Set APP_PORT in .env to a number from 1024 through 65535." . PHP_EOL);
    exit(1);
}

if (!TemplateTools\Port::isAvailable($port, $host)) {
    fwrite(STDERR, "Port {$port} is unavailable. Choose another APP_PORT or run composer setup; no process was stopped." . PHP_EOL);
    exit(1);
}

$public = dirname(__DIR__) . '/public';
$router = __DIR__ . '/server-router.php';
$command = sprintf(
    '%s -S %s:%d -t %s %s',
    escapeshellarg(PHP_BINARY),
    $host,
    $port,
    escapeshellarg($public),
    escapeshellarg($router),
);

fwrite(STDOUT, "Development server: http://{$host}:{$port}" . PHP_EOL);
passthru($command, $exitCode);
exit($exitCode);

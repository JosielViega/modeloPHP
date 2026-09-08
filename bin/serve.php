<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

$host = '127.0.0.1';
$port = filter_var(env('APP_PORT'), FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1024, 'max_range' => 65535],
]);

if ($port === false) {
    fwrite(STDERR, "Set APP_PORT in .env to a number from 1024 through 65535." . PHP_EOL);
    exit(1);
}

$connectionMessage = '';
set_error_handler(static function (int $severity, string $message) use (&$connectionMessage): bool {
    $connectionMessage = $message;
    return true;
});
try {
    $listener = fsockopen($host, $port, $connectionError, $connectionMessage, 0.5);
} finally {
    restore_error_handler();
}

if (is_resource($listener)) {
    fclose($listener);
    fwrite(STDERR, "Port {$port} already has a listener. Choose another APP_PORT; no process was stopped." . PHP_EOL);
    exit(1);
}

$errorMessage = '';
set_error_handler(static function (int $severity, string $message) use (&$errorMessage): bool {
    $errorMessage = $message;
    return true;
});
try {
    $socket = stream_socket_server("tcp://{$host}:{$port}", $errorCode, $errorMessage);
} finally {
    restore_error_handler();
}

if ($socket === false) {
    fwrite(STDERR, "Port {$port} is unavailable ({$errorMessage}). Choose another APP_PORT; no process was stopped." . PHP_EOL);
    exit(1);
}
fclose($socket);

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

<?php
/**
 * Environment loader for InfoHub.
 *
 * Reads variables from a .env file and makes them available via getenv(), $_ENV, and $_SERVER.
 */

$envFile = ROOT_PATH . '/.env';
if (!file_exists($envFile)) {
    return;
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    $line = trim($line);

    if ($line === '' || strpos($line, '#') === 0) {
        continue;
    }

    if (strpos($line, '=') === false) {
        continue;
    }

    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);

    if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
        (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
        $value = substr($value, 1, -1);
    }

    putenv("$name=$value");
    $_ENV[$name] = $value;
    $_SERVER[$name] = $value;
}

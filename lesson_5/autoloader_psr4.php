<?php

(new class {
    public function register(array $config): void
    {
        $mappings = [];
        $namespaceSeparator = '\\';

        foreach ($config as $namespacePrefix => $includePath) {
            $fullyQualifiedNamespacePrefix = rtrim(
                    $namespaceSeparator . ltrim($namespacePrefix, $namespaceSeparator),
                    $namespaceSeparator
                ) . $namespaceSeparator;
            $normalizedIncludePath = rtrim($includePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $mappings[$fullyQualifiedNamespacePrefix] = $normalizedIncludePath;
        }

        spl_autoload_register(function (string $className) use ($namespaceSeparator, $mappings) {
            $fullyQualifiedClassName = $namespaceSeparator . ltrim($className, $namespaceSeparator);

            foreach ($mappings as $prefix => $path) {
                if (str_starts_with($fullyQualifiedClassName, $prefix)) {
                    $relativeClassName = substr($fullyQualifiedClassName, strlen($prefix));
                    $fileName = $path . str_replace(
                            $namespaceSeparator,
                            DIRECTORY_SEPARATOR,
                            $relativeClassName
                        ) . '.php';

                    if (is_file($fileName)) {
                        require_once $fileName;
                    }
                }
            }
        });
    }
})->register([
    'Literato\\' => __DIR__ . DIRECTORY_SEPARATOR . 'Literato',
    'Generators\\' => __DIR__ . DIRECTORY_SEPARATOR . 'Generators',
]);
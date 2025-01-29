<?php
class APP_autoload
{
    const NAMESPACE = "app\\";

    public function __construct()
    {
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * @throws Exception
     */
    public function load(string $class): void
    {
        if (!str_starts_with($class, self::NAMESPACE)) {
            return; // stop and continue to another autoloader if registered
        }

        $filename = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen(self::NAMESPACE))) . '.php';

        if (is_readable($filename)) {
            require $filename;
        } else {
            throw new Exception("Class $class not found");
        }
    }
}

$APP_autoload = new APP_autoload();
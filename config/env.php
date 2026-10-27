<?php
/**
 * Lightweight Environment Variable Loader
 * 
 * Loads environment variables from .env file in the project root.
 * This implementation does not require external dependencies.
 */

class Env
{
    private static $loaded = false;
    private static $variables = [];

    /**
     * Load environment variables from .env file
     * 
     * @param string $path Path to .env file
     * @return bool Success status
     */
    public static function load($path = null)
    {
        if (self::$loaded) {
            return true;
        }

        if ($path === null) {
            $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
        }

        if (!file_exists($path) || !is_readable($path)) {
            // Silently fail if .env doesn't exist - fall back to defaults
            self::$loaded = true;
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE format
            if (strpos($line, '=') === false) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes if present
            if (preg_match('/^(["\'])(.*)\\1$/', $value, $matches)) {
                $value = $matches[2];
            }

            // Store in static array
            self::$variables[$key] = $value;

            // Also set as environment variable (if not already set by system)
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
            }
            if (!isset($_SERVER[$key])) {
                $_SERVER[$key] = $value;
            }
        }

        self::$loaded = true;
        return true;
    }

    /**
     * Get an environment variable value
     * 
     * @param string $key Variable name
     * @param mixed $default Default value if not found
     * @return mixed Variable value or default
     */
    public static function get($key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }

        // Check our internal array first
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }

        // Check $_ENV
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        // Check $_SERVER
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        // Check using getenv()
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        return $default;
    }

    /**
     * Check if an environment variable exists
     * 
     * @param string $key Variable name
     * @return bool
     */
    public static function has($key)
    {
        if (!self::$loaded) {
            self::load();
        }

        return isset(self::$variables[$key]) 
            || isset($_ENV[$key]) 
            || isset($_SERVER[$key]) 
            || getenv($key) !== false;
    }

    /**
     * Get all loaded environment variables
     * 
     * @return array
     */
    public static function all()
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$variables;
    }
}

// Auto-load on require
Env::load();

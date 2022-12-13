<?php

namespace Framework\HttpMethode;

class Cookie
{
    //same as session
    protected static Cookie|null $co = null;

    public static function getInstance()
    {
        if (null === self::$co) {
            self::$co = new Cookie();
        }
        return self::$co;
    }

    public static function get(string $key)
    {
        return $_COOKIE[$key] ?? null;
    }

    public static function set(string $key, $value, $time = 3600): void
    {
        if (is_array($value)) {
            setcookie(
                $key,
                json_encode($value),
                time() + $time,
                '/',
                null,
                false,
                true
            );
        } else {
            setcookie($key, $value, time() + $time, '/', null, false, true);
        }
    }

    public static function remove(string $key): void
    {
        unset($_COOKIE[$key]);
        setcookie($key, null, -1, '/', null, false, true);
    }

    public static function has(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    public static function all(): array
    {
        return $_COOKIE;
    }

    public static function hasCookie(): bool
    {
        return !empty($_COOKIE);
    }

    public static function destroy(): void
    {
        if (isset($_COOKIE)) {
            foreach ($_COOKIE as $key => $value) {
                unset($_COOKIE[$key]);
                setcookie($key, '', time() - 3600);
            }
        }
    }

    public static function start(): void
    {
        if (isset($_COOKIE)) {
            foreach ($_COOKIE as $key => $value) {
                $_COOKIE[$key] = $value;
            }
        }
    }
}

<?php

namespace Framework\HttpMethode;

class Post
{
    protected static Post|null $post = null;

    public static function getInstance()
    {
        if (null === self::$post) {
            self::$post = new Post();
        }
        return self::$post;
    }

    public static function get(string $key)
    {
        return $_POST[$key] ?? null;
    }

    public static function set(string $key, $value): void
    {
        $_POST[$key] = $value;
    }

    public static function remove(string $key): void
    {
        unset($_POST[$key]);
    }

    public static function has(string $key): bool
    {
        return empty($_POST[$key]);
    }

    public static function hasPost(): bool
    {
        return !empty($_POST);
    }

    public static function all(): array
    {
        return $_POST;
    }
}

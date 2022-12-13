<?php

namespace Framework\Response;

class Response
{
    public function __construct(
        protected string $template,
        protected array $args = []
    ) {
    }

    public static function buildWithController(
        string $controller,
        array $args
    ): Response {
        $controller = new $controller();

        if (!is_callable($controller)) {
            var_dump('You controller is not a valid callable!');
        }

        return $controller($args);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getJs(): array
    {
        return $this->args['js'] ?? [];
    }

    public function file(string $file): Response
    {
        $this->template = $file;
        return $this;
    }
}

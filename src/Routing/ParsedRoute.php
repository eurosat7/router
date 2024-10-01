<?php

declare(strict_types=1);

namespace app\Routing;

final readonly class ParsedRoute
{
    public function __construct(
        public string $path,
        public array $args = [],
        public array $request = [],
    ) {}
}
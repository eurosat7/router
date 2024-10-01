<?php

declare(strict_types=1);

namespace app\dto;

use RuntimeException;

final readonly class Config
{
    /**
     * @param array<string,string> $aliases
     */
    public function __construct(
        public string $viewPath,
        public array $aliases = [],
    ) {
        $this->checkConfig();
    }

    private function checkConfig(): void
    {
        if (!is_dir($this->viewPath)) {
            throw new RuntimeException(
                "Path for Views '$this->viewPath' does not exists.",
            );
        } elseif (!is_readable($this->viewPath)) {
            throw new RuntimeException(
                "Path for Views '$this->viewPath' is not readable.",
            );
        }
    }
}

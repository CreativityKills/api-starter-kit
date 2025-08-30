<?php

declare(strict_types=1);

namespace App\Contracts;

interface SupportsDocumentation
{
    /**
     * @return array<string, mixed>
     */
    public function rules();

    /**
     * @return array<string, mixed>
     */
    public function bodyParameters(): array;
}

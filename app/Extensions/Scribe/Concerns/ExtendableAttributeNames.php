<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Concerns;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * @mixin \Knuckles\Scribe\Extracting\Strategies\Metadata\GetFromMetadataAttributes
 */
trait ExtendableAttributeNames
{
    abstract protected static function fromDirectory(): string;

    protected static function generateNamespaceFromPath(string $filePath, ?string $baseNamespace = null): string
    {
        $relativePath = trim(str_replace([app_path(), '.php'], '', $filePath), '/\\');
        $namespacePath = str_replace(['/', '\\'], '\\', $relativePath);

        return sprintf('%s\\%s', $baseNamespace ?? 'App', $namespacePath);
    }

    /**
     * @param  'Groups'|'Responses'  $type
     * @return string
     */
    protected static function usingDirectory(string $type): string
    {
        return sprintf('%s/Http/Documentation/%s', app_path(), $type);
    }

    /**
     * @return string[]
     */
    protected static function excludingFiles(): array
    {
        return [
            'GenericResponse.php',
            'GenericGroup.php',
            'GenericSubGroup.php',
        ];
    }

    public static function extendAttributes(): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(self::fromDirectory())
        );

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            if (in_array($file->getFilename(), self::excludingFiles())) {
                continue;
            }

            self::registerAttributeClass(
                self::generateNamespaceFromPath(filePath: $file->getPathname())
            );
        }

        self::$attributeNames = array_unique(self::$attributeNames);
    }

    public static function registerAttributeClass(string $class): void
    {
        self::$attributeNames[] = $class;
    }
}

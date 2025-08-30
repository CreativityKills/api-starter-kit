<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Concerns;

/**
 * @mixin \Knuckles\Scribe\Extracting\Strategies\Metadata\GetFromMetadataAttributes
 */
trait ExtendableAttributeNames
{
    abstract protected static function fromDirectory(): string;

    abstract protected static function namespace(string $appending = ''): string;

    /**
     * @param  'Metadata'|'Response'  $type
     * @return string
     */
    protected static function usingDirectory(string $type): string
    {
        return sprintf('%s/Http/Documentation/%s/*.php', app_path(), $type);
    }

    /**
     * @param  'Metadata'|'Response'  $type
     * @param  string  $appending
     * @return string
     */
    protected static function usingNamespace(string $type, string $appending = ''): string
    {
        return "\\App\\Http\\Documentation\\{$type}\\{$appending}";
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
        $files = glob(self::fromDirectory());
        if (empty($files)) {
            return;
        }

        $attributeFiles = array_filter($files, fn($file) => !in_array(basename($file), self::excludingFiles()));

        foreach ($attributeFiles as $attributeFile) {
            $attributeName = pathinfo($attributeFile, PATHINFO_FILENAME);

            self::registerAttributeClass(
                self::namespace(appending: $attributeName)
            );
        }

        self::$attributeNames = array_unique(self::$attributeNames);
    }

    public static function registerAttributeClass(string $class): void
    {
        self::$attributeNames[] = $class;
    }
}

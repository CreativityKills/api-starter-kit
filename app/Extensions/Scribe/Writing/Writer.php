<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Writing;

use Knuckles\Scribe\Tools\Utils;
use Knuckles\Scribe\Writing\Writer as BaseWriter;

class Writer extends BaseWriter
{
    /**
     * @throws \Exception
     */
    protected function performFinalTasksForLaravelType(): void
    {
        if (is_null($this->laravelTypeOutputPath)) {
            return;
        }

        if (!is_dir($this->laravelTypeOutputPath)) {
            mkdir($this->laravelTypeOutputPath, 0777, true);
        }

        $publicDirectory = public_path();
        if (!is_dir($publicDirectory.$this->laravelAssetsPath)) {
            mkdir($publicDirectory.$this->laravelAssetsPath, 0777, true);
        }

        // Use copy instead of rename
        copy("{$this->staticTypeOutputPath}/index.html", "$this->laravelTypeOutputPath/index.blade.php");

        // Copy assets instead of renaming directories
        Utils::copyDirectory("$this->staticTypeOutputPath/css", $publicDirectory.$this->laravelAssetsPath.'/css');
        Utils::copyDirectory("$this->staticTypeOutputPath/js", $publicDirectory.$this->laravelAssetsPath.'/js');
        Utils::copyDirectory("$this->staticTypeOutputPath/images", $publicDirectory.$this->laravelAssetsPath.'/images');

        $c = file_get_contents("$this->laravelTypeOutputPath/index.blade.php");
        $postmanOutputPath = $this->paths->outputPath('postman', '.');
        $openapiOutputPath = $this->paths->outputPath('openapi', '.');

        // @phpstan-ignore-next-line
        $c = preg_replace('#href="\.\./docs/css/(.+?)"#', 'href="{{ asset("'.$this->laravelAssetsPath.'/css/$1") }}"', $c);
        // @phpstan-ignore-next-line
        $c = preg_replace('#src="\.\./docs/(js|images)/(.+?)"#', 'src="{{ asset("'.$this->laravelAssetsPath.'/$1/$2") }}"', $c);
        // @phpstan-ignore-next-line
        $c = str_replace('href="../docs/collection.json"', 'href="{{ route("'.$postmanOutputPath.'") }}"', $c);
        $c = str_replace('href="../docs/openapi.yaml"', 'href="{{ route("'.$openapiOutputPath.'") }}"', $c);
        $c = str_replace('url="../docs/openapi.yaml"', 'url="{{ route("'.$openapiOutputPath.'") }}"', $c);
        $c = str_replace('Url="../docs/openapi.yaml"', 'Url="{{ route("'.$openapiOutputPath.'") }}"', $c);

        file_put_contents("$this->laravelTypeOutputPath/index.blade.php", $c);
    }
}

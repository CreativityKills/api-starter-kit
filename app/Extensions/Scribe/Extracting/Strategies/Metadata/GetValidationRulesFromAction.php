<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Extracting\Strategies\Metadata;

use ReflectionClass;
use ReflectionException;
use ReflectionUnionType;
use ReflectionFunctionAbstract;
use App\Contracts\SupportsDocumentation;
use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Extracting\Strategies\Strategy;
use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Extracting\ParsesValidationRules;

class GetValidationRulesFromAction extends Strategy
{
    use ParsesValidationRules;

    /**
     * @param  array<array-key, mixed>  $settings
     * @return array<string, mixed>|null
     */
    public function __invoke(ExtractedEndpointData $endpointData, array $settings = []): ?array
    {
        if (! $endpointData->method instanceof ReflectionFunctionAbstract) {
            return [];
        }

        return $this->getParametersFromAction($endpointData->method);
    }

    /**
     * @return array<string, mixed>
     */
    public function getParametersFromAction(ReflectionFunctionAbstract $method): array
    {
        $actionClass = $this->getCompatibleActionReflectionClass($method)?->getName();
        if ($actionClass === null) {
            return [];
        }

        $action = app($actionClass);

        assert($action instanceof SupportsDocumentation, 'Action must implement SupportsScribeDocumentation');

        return $this->normaliseArrayAndObjectParameters(
            $this->getParametersFromValidationRules($action->rules(), $action->bodyParameters())
        );
    }

    /**
     * @return ReflectionClass<object>|null
     */
    protected function getCompatibleActionReflectionClass(ReflectionFunctionAbstract $method): ?ReflectionClass
    {
        foreach ($method->getParameters() as $argument) {
            $type = $argument->getType();

            if (
                blank($type) ||
                $type instanceof ReflectionUnionType ||
                ! method_exists($type, 'getName') ||
                ! class_exists($type->getName())
            ) {
                continue;
            }

            try {
                $argumentClass = new ReflectionClass($type->getName());
            } catch (ReflectionException) {
                continue;
            }

            if ($argumentClass->isSubclassOf(FormRequest::class)) {
                continue;
            }

            if ($argumentClass->implementsInterface(SupportsDocumentation::class)) {
                return $argumentClass;
            }
        }

        return null;
    }
}

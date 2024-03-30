<?php

declare(strict_types=1);

namespace Overblog\GraphQLBundle\Config\Parser;

use Overblog\GraphQLBundle\Annotation\Annotation;
use Overblog\GraphQLBundle\Config\Parser\MetadataParser\MetadataParser;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

final class AttributeParser extends MetadataParser
{
    public const METADATA_FORMAT = '#[%s]';

    public static function getMetadatas(Reflector $reflector): array
    {
        $attributes = [];

        switch (true) {
            case $reflector instanceof ReflectionClass:
            case $reflector instanceof ReflectionMethod:
            case $reflector instanceof ReflectionProperty:
            case $reflector instanceof ReflectionClassConstant:
                if (is_callable([$reflector, 'getAttributes'])) {
                    $attributes = $reflector->getAttributes(Annotation::class, ReflectionAttribute::IS_INSTANCEOF);
                }
        }

        // @phpstan-ignore-next-line
        return array_map(fn (ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes);
    }
}

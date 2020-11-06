<?php

declare(strict_types=1);

namespace Overblog\GraphQLBundle\Definition;

use LogicException;
use Overblog\GraphQLBundle\Resolver\MutationResolver;
use Overblog\GraphQLBundle\Resolver\ResolverResolver;
use Overblog\GraphQLBundle\Resolver\TypeResolver;
use function json_encode;
use function sprintf;

/**
 * Container for special services to be passed to all generated types.
 */
final class GraphQLServices
{
    private array $services;
    private TypeResolver $types;
    private ResolverResolver $resolverResolver;
    private MutationResolver $mutationResolver;

    public function __construct(
        TypeResolver $typeResolver,
        ResolverResolver $resolverResolver,
        MutationResolver $mutationResolver,
        array $services = []
    ) {
        $this->types = $typeResolver;
        $this->resolverResolver = $resolverResolver;
        $this->mutationResolver = $mutationResolver;
        $this->services = $services;
    }

    /**
     * @return mixed
     */
    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new LogicException("GraphQL service '$name' could not be located. You should define it.");
        }

        return $this->services[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }

    public function query(string $alias, ...$args)
    {
        return $this->resolverResolver->resolve([$alias, $args]);
    }

    public function mutation(string $alias, ...$args)
    {
        return $this->mutationResolver->resolve([$alias, $args]);
    }

    public function getType(string $typeName)
    {
        return $this->types->resolve($typeName);
    }
}

<?php namespace Quasar\SearchEngine\GraphQL\Resolvers;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Quasar\SearchEngine\Models\Index;
use Quasar\SearchEngine\Services\IndexService;
use Quasar\Core\GraphQL\Resolvers\CoreResolver;

class IndexResolver extends CoreResolver
{
    public function __construct(Index $model, IndexService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }

    public function search($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->service->search($args['query']);
    }
}

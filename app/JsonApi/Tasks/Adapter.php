<?php

declare(strict_types=1);

namespace App\JsonApi\Tasks;

use App\Task;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\BelongsTo;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Elasticsearch\Common\Exceptions\ElasticsearchException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Task(), $paging);
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters): void
    {
        if ($elasticsQuery = $filters->get('query')) {
            try {
                $searchResult = Task::rawSearch()
                    ->query([
                        "query_string" => [
                            "query" => $elasticsQuery,
                            "default_operator" => "or",
                            "fields" => [
                                'title', 'description'
                            ]
                        ]
                    ])
                    ->from($filters->get('from', 0))
                    ->size($filters->get('size', 20))
                    ->execute();

                $query->whereIn('id', $searchResult->models()->pluck('id')->toArray());
            } catch (ElasticsearchException $e) {
                abort(400);
            }
        }
    }

    /**
     * @return BelongsTo
     */
    protected function user(): BelongsTo
    {
        return $this->belongsTo();
    }
}

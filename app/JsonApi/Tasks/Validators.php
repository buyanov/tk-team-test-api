<?php

declare(strict_types=1);

namespace App\JsonApi\Tasks;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = ['query', 'size', 'from'];

    protected $allowedPagingParameters = ['number', 'size'];

    /**
     * Get resource validation rules.
     *
     * @param mixed|null $record
     *      the record being updated, or null if creating a resource.
     * @return mixed
     */
    protected function rules($record = null): array
    {
        return [
            'title' => 'required|string|min:1|max:255',
            'description' => 'required|string|min:1',
            'status' => "required",
        ];
    }

    /**
     * Get query parameter validation rules.
     *
     * @return array
     */
    protected function queryRules(): array
    {
        return [
            'page.number' => 'filled|numeric|min:1',
            'page.size' => 'filled|numeric|between:1,20',
            'filter.query' => 'filled|string',
            'filter.size' => 'filled|numeric|between:1,100',
            'filter.from' => 'filled|numeric|min:0',
        ];
    }
}

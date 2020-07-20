<?php

declare(strict_types=1);

namespace App\JsonApi\Users;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'users';

    /**
     * @param \App\User $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\User $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->name,
            'email' => $resource->email,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    /**
     * @param object $user
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($user, $isPrimary, array $includeRelationships): array
    {
        return [
            'tasks' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::DATA => static function () use ($user) {
                    return $user->tasks;
                }
            ],
        ];
    }
}

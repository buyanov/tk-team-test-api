<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use UseUuidKey;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class)->withTimestamps();
    }

    /**
     * @param Ability $ability
     */
    public function allowTo(Ability $ability): void
    {
        $this->abilities()->sync($ability, false);
    }
}

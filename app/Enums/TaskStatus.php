<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static new(): string
 * @method static wip(): string
 * @method static closed(): string
 */

class TaskStatus extends Enum
{
    protected const NEW = 'new';

    protected const WIP = 'wip';

    protected const CLOSED = 'closed';
}

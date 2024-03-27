<?php

declare(strict_types=1);

namespace Modules\Cms\Actions;

use Spatie\QueueableAction\QueueableAction;

final class GetStyleClassByViewAction
{
    use QueueableAction;

    public function execute(string $view = ''): string
    {
        return app(GetConfigKeyByViewAction::class)->execute($view, 'class');
    }
}

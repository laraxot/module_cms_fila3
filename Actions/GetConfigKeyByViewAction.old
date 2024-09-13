<?php

declare(strict_types=1);

namespace Modules\Cms\Actions;

use Illuminate\Support\Str;
use Modules\Xot\Services\FileService;
use Spatie\QueueableAction\QueueableAction;

final class GetConfigKeyByViewAction
{
    use QueueableAction;

    public function execute(string $view, string $key): string
    {
        $config_path = inAdmin() ? 'adm_theme' : 'pub_theme';
        $config_key = '::'.Str::after($view, '::components.').'.'.$key;
        $key = $config_path.$config_key;

        $res = FileService::config($key);
        if (is_string($res)) {
            return $res;
        }

        $key1 = 'cms'.$config_key;

        $res = FileService::config($key1);

        if (is_string($res)) {
            FileService::configCopy($key1, $key);

            return $res;
        }

        throw new \Exception('create config ['.$key.'] or ['.$key1.']');
    }
}

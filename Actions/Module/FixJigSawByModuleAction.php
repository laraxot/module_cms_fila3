<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Module;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Modules\Xot\Services\FileService;
use Nwidart\Modules\Laravel\Module;

use function Safe\realpath;

use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\Finder\SplFileInfo;

final class FixJigSawByModuleAction
{
    use QueueableAction;

    public function execute(Module $module): array
    {
        $res = [];
        $stubs_dir = realpath(__DIR__.'/../../Console/Commands/stubs/docs');
        if (false == $stubs_dir) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }

        $stubs = File::allFiles($stubs_dir);
        foreach ($stubs as $stub) {
            /*
            dddx([
                'getRelativePath' => $stub->getRelativePath(), // ""
                'getRelativePathname' => $stub->getRelativePathname(), // "config.stub"
                'getFilenameWithoutExtension' => $stub->getFilenameWithoutExtension(), // "config"
                // 'getContents' => $stub->getContents(),
                'getPath' => $stub->getPath(), // "/var/www/html/_bases/base_pfed/laravel/Modules/Cms/Console/Commands/stubs/docs"
                'getFilename' => $stub->getFilename(), // "config.stub"
                'getExtension' => $stub->getExtension(), // "stub"
                'getBasename' => $stub->getBasename(), // "config.stub"
                'getPathname' => $stub->getPathname(), // "/var/www/html/_bases/base_pfed/laravel/Modules/Cms/Console/Commands/stubs/docs/config.stub"
                'isFile' => $stub->isFile(),
                // 'getLinkTarget' => $stub->getLinkTarget(),
                'getRealPath' => $stub->getRealPath(), //"/var/www/html/_bases/base_pfed/laravel/Modules/Cms/Console/Commands/stubs/docs/config.stub"
                //'getFileInfo' => $stub->getFileInfo(),
                //'getPathInfo' => $stub->getPathInfo(),
                //'methods' => get_class_methods($stub),
            ]);
            */
            if (! $stub->isFile()) {
                continue;
            }
            
            if ('stub' != $stub->getExtension()) {
                continue;
            }
            
            $res[] = $this->publish($stub, $module);
        }

        return $res;
    }

    public function publish(SplFileInfo $stub, Module $module): string
    {
        $filename = str_replace('.stub', '', $stub->getRelativePathname());
        $file_path = $module->getPath().'/docs/'.$filename;
        $file_path = FileService::fixPath($file_path);
        /*
        //mkdir(): Permission denied
        if (! is_dir(dirname($file_path))) {
            (new Filesystem())->makeDirectory(dirname($file_path));
        }
        */

        $replace = [
            'ModuleName' => $module->getName(),
        ];

        $file_content = str_replace(
            array_keys($replace),
            array_values($replace),
            $stub->getContents(),
        );
        File::put($file_path, $file_content);

        return $file_path;
    }
}

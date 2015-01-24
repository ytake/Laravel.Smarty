<?php
namespace Ytake\LaravelSmarty\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container as ContainerContract;

/**
 * Class PublishCommand
 * @package Ytake\LaravelSmarty\Console
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class PublishCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $name = 'ytake:smarty-config-publish';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'publish configure file for ytake/laravel-smarty';

    /** @var ContainerContract  */
    protected $app;

    /**
     * @param ContainerContract $app
     */
    public function __construct(ContainerContract $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        /** @var \Illuminate\Filesystem\Filesystem $filesystem */
        $filesystem = $this->app['files'];
        $packageConfigure = $this->app->make('ytake.laravel-smarty.config');
        $path = $this->app->configPath() . '/ytake/laravel-smarty';
        $file = $path . '/config.php';

        if(!$filesystem->isDirectory($path)) {
            if(!$filesystem->makeDirectory($path, 0755, true)) {
                throw new \ErrorException("permission error");
            }
        }
        if(!$filesystem->exists($file)) {
            if($filesystem->copy($packageConfigure, $file)) {
                $this->line('<info>published:<comment>' . $file . '</comment>');
                return;
            }
        }
        $this->error("file exists");
   }

}

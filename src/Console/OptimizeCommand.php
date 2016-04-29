<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2014-2016 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelSmarty\Console;

use Smarty;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Contracts\Config\Repository as ConfigContract;

/**
 * Class OptimizeCommand
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class OptimizeCommand extends Command
{
    /** @var Smarty */
    protected $smarty;

    /** @var ConfigContract */
    protected $config;

    /**
     * @param Smarty         $smarty
     * @param ConfigContract $config
     */
    public function __construct(Smarty $smarty, ConfigContract $config)
    {
        parent::__construct();
        $this->smarty = $smarty;
        $this->config = $config;
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ytake:smarty-optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'compiles all known templates';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $configureFileExtension = $this->config->get('ytake-laravel-smarty.extension', 'tpl');
        $fileExtension = (is_null($this->option('extension')))
            ? $configureFileExtension : $this->option('extension');
        ob_start();
        $compileFiles = $this->smarty->compileAllTemplates(
            $fileExtension, $this->forceCompile()
        );
        $contents = ob_get_contents();
        ob_get_clean();
        $this->info("{$compileFiles} template files recompiled");
        $this->comment(str_replace("<br>", "\n", trim($contents)));
        return;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['extension', 'e', InputOption::VALUE_OPTIONAL, 'specified smarty file extension'],
            ['force', null, InputOption::VALUE_NONE, 'compiles template files found in views directory'],
        ];
    }

    /**
     * @return bool
     */
    protected function forceCompile()
    {
        if ($this->option('force')) {
            return true;
        }
        return false;
    }
}

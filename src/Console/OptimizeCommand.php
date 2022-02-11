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
 * Copyright (c) 2014-2022 Yuuki Takezawa
 *
 */

declare(strict_types=1);

namespace Ytake\LaravelSmarty\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Symfony\Component\Console\Input\InputOption;
use Ytake\LaravelSmarty\Smarty;

use function is_null;
use function ob_get_clean;
use function ob_get_contents;
use function ob_start;
use function str_replace;
use function trim;

/**
 * Class OptimizeCommand
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class OptimizeCommand extends Command
{
    /**
     * @param Smarty $smarty
     * @param ConfigContract $config
     */
    public function __construct(
        protected Smarty $smarty,
        Protected ConfigContract $config
    ) {
        parent::__construct();
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
    protected $description = 'Compile all known templates.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $configureFileExtension = $this->config->get('ytake-laravel-smarty.extension', 'tpl');
        $fileExtension = (is_null($this->option('extension')))
            ? $configureFileExtension : $this->option('extension');
        ob_start();
        $compileFiles = $this->smarty->compileAllTemplates(
            $fileExtension,
            $this->forceCompile()
        );
        $contents = ob_get_contents();
        ob_get_clean();
        $this->comment(str_replace("<br>", "", trim($contents)));
        $this->info("{$compileFiles} template files recompiled");
        return 0;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['extension', 'e', InputOption::VALUE_OPTIONAL, 'Specified smarty file extension'],
            ['force', null, InputOption::VALUE_NONE, 'Compiles template files found in views directory'],
        ];
    }

    /**
     * @return bool
     */
    protected function forceCompile(): bool
    {
        if ($this->option('force')) {
            return true;
        }
        return false;
    }
}

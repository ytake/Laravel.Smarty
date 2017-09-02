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
 * Copyright (c) 2014-2017 Yuuki Takezawa
 *
 */

namespace Ytake\LaravelSmarty\Console;

use Ytake\LaravelSmarty\Smarty;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CacheClearCommand
 *
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class CacheClearCommand extends Command
{
    /** @var Smarty */
    protected $smarty;

    /**
     * @param Smarty $smarty
     */
    public function __construct(Smarty $smarty)
    {
        parent::__construct();
        $this->smarty = $smarty;
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ytake:smarty-clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the Smarty cache.';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        // clear all cache
        if (is_null($this->option('file'))) {
            $this->smarty->clearAllCache($this->option('time'));
            $this->info('Smarty cache cleared!');

            return 0;
        }
        // file specified
        if (!$this->smarty->clearCache($this->option('file'), $this->option('cache_id'), null, $this->option('time'))) {
            $this->error('Specified file not found');

            return 1;
        }
        $this->info('Specified file was cache cleared!');

        return 0;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['file', 'f', InputOption::VALUE_OPTIONAL, 'Specify file'],
            ['time', 't', InputOption::VALUE_OPTIONAL, 'Clear all of the files that are specified duration time'],
            ['cache_id', 'cache', InputOption::VALUE_OPTIONAL, 'Specified cache_id groups'],
        ];
    }
}

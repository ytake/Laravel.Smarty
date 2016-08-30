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

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Ytake\LaravelSmarty\SmartyFactory;

/**
 * Class ClearCompiledCommand
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class ClearCompiledCommand extends Command
{
    /** @var SmartyFactory */
    protected $smartyFactory;

    /**
     * @param SmartyFactory $smartyFactory
     */
    public function __construct(SmartyFactory $smartyFactory)
    {
        parent::__construct();
        $this->smartyFactory = $smartyFactory;
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ytake:smarty-clear-compiled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the compiled smarty file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function fire()
    {
        $removedFiles = $this->smartyFactory
            ->getSmarty()
            ->clearCompiledTemplate($this->option('file'), $this->option('compile_id'));

        if ($removedFiles > 0) {
            $this->info("removed $removedFiles file" . ($removedFiles > 1 ? 's' : '') . '.');
        }

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
            ['file', 'f', InputOption::VALUE_OPTIONAL, 'specify file'],
            ['compile_id', 'compile', InputOption::VALUE_OPTIONAL, 'specified compile_id'],
        ];
    }
}

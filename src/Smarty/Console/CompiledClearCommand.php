<?php
namespace Ytake\LaravelSmarty\Console;

use Smarty;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CompiledClearCommand
 * @package Ytake\LaravelSmarty\Console
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class CompiledClearCommand extends Command
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
     * @var string
     */
    protected $name = 'ytake:smarty-clear-compiled';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Remove the compiled smarty file';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        if($this->smarty->clearCompiledTemplate($this->option('file'), $this->option('compile_id'))) {
            $this->info('done.');
            return;
        }
        return;
    }

    /**
     * Get the console command options.
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

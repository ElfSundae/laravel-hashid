<?php

namespace ElfSundae\Laravel\Hashid\Console;

use Illuminate\Console\Command;
use Jenssegers\Optimus\Energon;
use Symfony\Component\Console\Input\InputOption;

class OptimusGenerateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'hashid:optimus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Optimus numbers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table(
            ['prime', 'inverse', 'random'],
            $this->generateOptimusNumbers($this->getTimes())
        );
    }

    /**
     * Get "times" option value.
     *
     * @return int
     */
    protected function getTimes()
    {
        $times = (int) $this->option('times');

        return max(1, min($times, 100));
    }

    /**
     * Generate Optimus numbers.
     *
     * @param  int  $times
     * @return array
     */
    protected function generateOptimusNumbers($times = 1)
    {
        $result = [];

        for ($i = 0; $i < $times; $i++) {
            $result[] = Energon::generate();
        }

        return $result;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['times', 't', InputOption::VALUE_OPTIONAL, 'Times to generate', 1],
        ];
    }
}

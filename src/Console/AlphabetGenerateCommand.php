<?php

namespace ElfSundae\Laravel\Hashid\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class AlphabetGenerateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'hashid:alphabet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a random alphabet for Hashid encoding';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        for ($i = 0; $i < $this->getTimes(); $i++) {
            $this->comment(
                $this->generateRandomAlphabet($this->option('characters'))
            );
        }
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
     * Generate random alphabet.
     *
     * @return string
     */
    protected function generateRandomAlphabet($characters)
    {
        return str_shuffle(count_chars($characters, 3));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['characters', 'c', InputOption::VALUE_OPTIONAL, 'Use custom characters', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'],
            ['times', 't', InputOption::VALUE_OPTIONAL, 'Times to generate', 1],
        ];
    }
}

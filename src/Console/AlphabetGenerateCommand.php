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
    protected $description = 'Generate a random alphabet for Hashid encoder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment(
            $this->generateRandomAlphabet(
                $this->option('characters'),
                $this->getTimes()
            )
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
     * Generate random alphabet.
     *
     * @param  string  $characters
     * @param  int  $times
     * @return string
     */
    protected function generateRandomAlphabet($characters, $times = 1)
    {
        $result = [];

        for ($i = 0; $i < $times; $i++) {
            $result[] = str_shuffle(count_chars($characters, 3));
        }

        return implode(PHP_EOL, $result);
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

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
    protected $description = 'Generate random alphabets';

    /**
     * The default characters.
     *
     * @var string
     */
    protected $defaultCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $alphabets = $this->generateRandomAlphabets(
            $this->getTimes(),
            (string) $this->option('characters')
        );

        $this->comment(implode(PHP_EOL, $alphabets));
    }

    /**
     * Get "times" option value.
     *
     * @return int
     */
    protected function getTimes()
    {
        return max(1, min(100, (int) $this->option('times')));
    }

    /**
     * Generate random alphabets.
     *
     * @param  int  $times
     * @param  string  $characters
     * @return array
     */
    protected function generateRandomAlphabets($times = 1, $characters = null)
    {
        $characters = $characters ?: $this->defaultCharacters;

        $result = [];
        for ($i = 0; $i < $times; $i++) {
            $result[] = str_shuffle(count_chars($characters, 3));
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
            ['characters', 'c', InputOption::VALUE_OPTIONAL, 'Use custom characters', $this->defaultCharacters],
            ['times', 't', InputOption::VALUE_OPTIONAL, 'Times to generate', 1],
        ];
    }
}

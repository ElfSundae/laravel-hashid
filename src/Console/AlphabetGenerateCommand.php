<?php

namespace ElfSundae\Laravel\Hashid\Console;

use Illuminate\Console\Command;

class AlphabetGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hashid:alphabet
        {--c|characters=0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ : Use custom characters}
        {--t|times=1 : Times to generate}';

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
        for ($i = 0; $i < $this->option('times'); $i++) {
            $this->comment(
                $this->generateRandomAlphabet($this->option('characters'))
            );
        }
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
}

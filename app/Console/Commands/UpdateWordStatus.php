<?php

namespace App\Console\Commands;

use App\Models\Word;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateWordStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-word-status {words}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $ids = explode(";", $this->argument('words'));
        Log::info("IDS: " . json_encode($ids));
        foreach ($ids as $item) {
            $word = Word::find($item);
            if (!$word->isValidated) {
                $word->isValidated = true;
                $word->save();
            }
        }
    }
}

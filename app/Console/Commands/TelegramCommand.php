<?php

namespace App\Console\Commands;

use App\Http\Services\Telegram\TelegramReaderService;
use Illuminate\Console\Command;

class TelegramCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(TelegramReaderService $telegramReader)
    {
        try {
            $offset = 0;

            while (true) {
                $offset = $telegramReader->getUpdates($offset);
                $this->info('offset: ' . $offset . '. At work...');
                sleep(config('telegram.sleep'));
            }
        } catch (\InvalidArgumentException $e) {
            return $e->getMessage();
        }
    }
}

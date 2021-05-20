<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Browsershot\FileManager;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Cleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup {days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the Screenshot Folder';

    private FileManager $fileManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = (int)$this->argument('days');

        $this->fileManager->listContentByLastModified()
            ->map(callback: function ($file) {
                $file['timestamp'] = Carbon::createFromTimestamp($file['timestamp']);
                return $file;
            })
            ->filter(callback: fn($file) => $file['timestamp']->lt(Carbon::now()->subDays($days)))
            ->each(callback: fn($file) => $this->fileManager->delete($file['path']));

        $this->info("All screenshots older than " . $days . ' days have been deleted');
        return 0;
    }
}

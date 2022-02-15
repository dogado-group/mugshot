<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Browsershot\StorageManager;
use Carbon\Carbon;
use Illuminate\Console\Command;
use League\Flysystem\FileAttributes;

class Cleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mugshot:cleanup {days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the Screenshot Folder';

    private StorageManager $fileManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StorageManager $fileManager)
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

        $content = $this->fileManager->listContent()
            ->filter(function (FileAttributes $file) use ($days): bool {
                return Carbon::createFromTimestamp($file->lastModified())
                    ->lt(Carbon::now()->subDays($days));
            });

        $count = $content->count();

        $content->each(callback: fn(FileAttributes $file) => $this->fileManager->delete($file['path']));

        $this->info("Deleted $count screenshots that were over $days days old.");

        return 0;
    }
}

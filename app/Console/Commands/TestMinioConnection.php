<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestMinioConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minio:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test connection to MinIO / S3 storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing MinIO/S3 connection...');

        try {
            $fileName = 'minio-test-connection.txt';
            
            // Attempt to write a file
            Storage::disk('s3')->put($fileName, 'This is a test connection to MinIO.');
            
            // Verify the file exists
            if (Storage::disk('s3')->exists($fileName)) {
                // Delete the file to clean up
                Storage::disk('s3')->delete($fileName);
                $this->info('✅ Success! Connected to MinIO and performed write/read/delete operations successfully.');
            } else {
                $this->error('❌ Connected to MinIO, but failed to verify the uploaded test file.');
            }
        } catch (\Exception $e) {
            $this->error('❌ Failed to connect to MinIO! Error details:');
            $this->error($e->getMessage());
        }
    }
}

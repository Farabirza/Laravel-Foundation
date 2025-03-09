<?php

namespace App\Console\Commands;

use App\Models\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateThemesConfigCommand extends Command
{
    protected $signature = 'command:update-themes-config';

    protected $description = 'command:update-themes-config';

    public function handle()
    {
        $directory = public_path('themes/config');

        if (!File::exists($directory)) {
            $this->error("Directory does not exist: $directory");
            return;
        }

        $files = File::files($directory);

        if (empty($files)) {
            $this->info("No files found in directory: $directory");
            return;
        }

        foreach ($files as $file) {
            $file_name = $file->getFilename();
            $code = explode('.', $file_name)[0];
            $this->info("processing config: " . $file->getFilename());
            $theme = Theme::where('code', $code)->first();
            if(!$theme) {
                $this->info("theme not found, skipped");
                continue;
            } else {
                try {
                    $theme->update([
                        'config' => File::get($file)
                    ]);
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                }
            }
        }
        $this->info("update themes config completed");
    }
}

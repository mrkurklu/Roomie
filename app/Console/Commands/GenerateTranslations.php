<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslationService;

class GenerateTranslations extends Command
{
    protected $signature = 'translations:generate';
    protected $description = 'Generate translation files for all supported languages';

    public function handle()
    {
        $languages = TranslationService::getSupportedLanguages();
        $baseLanguage = 'tr';
        $basePath = resource_path('lang/' . $baseLanguage . '/messages.php');
        
        if (!file_exists($basePath)) {
            $this->error('Base language file not found: ' . $basePath);
            return 1;
        }

        $baseTranslations = require $basePath;

        foreach ($languages as $code => $name) {
            if ($code === $baseLanguage) {
                continue; // Skip base language
            }

            $targetPath = resource_path('lang/' . $code);
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }

            $targetFile = $targetPath . '/messages.php';
            $translated = [];

            $this->info("Translating to {$name} ({$code})...");

            foreach ($baseTranslations as $key => $value) {
                if (is_string($value)) {
                    $translated[$key] = TranslationService::translate($value, $code, $baseLanguage);
                } else {
                    $translated[$key] = $value;
                }
            }

            $content = "<?php\n\nreturn " . var_export($translated, true) . ";\n";
            file_put_contents($targetFile, $content);

            $this->info("✓ Created: {$targetFile}");
        }

        $this->info('All translation files generated successfully!');
        return 0;
    }
}


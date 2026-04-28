<?php

namespace AhmedAliraqi\LangGenerator\Console\Commands;

use AhmedAliraqi\LangGenerator\Manager;
use Illuminate\Console\Command;
use Laraeast\LaravelLocales\Facades\Locales;

class LangGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for all lang keys from views and put them to json lang files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        // Get all matched translation keys from the project
        $matches = array_unique(app(Manager::class)->getMatched());

        // Store only JSON keys (non-PHP translation keys)
        $jsonKeys = [];

        $langPaths = config('lang-generator.lang_paths', []);

        /**
         * Build list of PHP lang files per locale
         * [locale => [fileName => true]]
         */
        $langFiles = [];

        foreach (Locales::get() as $locale) {
            $langCode = $locale->getCode();

            foreach ($langPaths as $pathTemplate) {
                $path = str_replace('{lang}', $langCode, $pathTemplate);

                foreach (glob($path.'/*.php') as $file) {
                    $fileName = basename($file, '.php');
                    $langFiles[$langCode][$fileName] = true;
                }
            }
        }

        foreach ($matches as $key) {

            if (empty($key)) {
                continue;
            }

            $parts = explode('.', $key);
            $firstSegment = $parts[0];

            foreach (Locales::get() as $locale) {

                $langCode = $locale->getCode();

                if (isset($langFiles[$langCode][$firstSegment])) {
                    continue 2;
                }
            }

            $jsonKeys[] = $key;
        }

        if (empty($jsonKeys)) {
            $this->info('No JSON keys found.');

            return Command::FAILURE;
        }

        foreach (Locales::get() as $locale) {

            $langCode = $locale->getCode();
            $jsonPath = base_path("lang/{$langCode}.json");

            $data = file_exists($jsonPath)
                ? json_decode(file_get_contents($jsonPath), true)
                : [];

            $updated = false;

            foreach ($jsonKeys as $key) {

                if (! isset($data[$key])) {
                    $data[$key] = $key;
                    $updated = true;

                    $this->line("✔ {$key}");
                }
            }

            if ($updated) {
                file_put_contents(
                    $jsonPath,
                    json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                );

                $this->info("Updated JSON: {$jsonPath}");
            } else {
                $this->info("No changes for: {$jsonPath}");
            }
        }

        $this->info('🚀 JSON generation completed');

        return Command::SUCCESS;
    }
}

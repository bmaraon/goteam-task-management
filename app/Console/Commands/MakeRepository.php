<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name} {--model=} {--table=}';
    protected $description = 'Generate repository and interface (optionally tied to a model, policy, resource, and controller) using stubs';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $model = $this->option('model') ? Str::studly($this->option('model')) : null;
        $table = $this->option('table');
        $interface = "{$name}RepositoryInterface";
        $repository = "{$name}Repository";

        if ($model) {
            $this->call('make:model', [
                'name' => $model,
                '--migration' => empty($table),
                '--factory' => true,
                '--policy' => true,
                '--resource' => true
            ]);
        }

        // Create repository interface
        $interfaceStubName = $model ? 'repository_interface' : 'empty_repository_interface';

        $this->makeFromStub(
            base_path("stubs/repository/{$interfaceStubName}.stub"),
            app_path("Repositories/Contracts/{$interface}.php"),
            [
                '{{ name }}' => $name
            ]
        );

        // Create repository
        $repositoryStubName = $model ? 'repository' : 'empty_repository';

        $this->makeFromStub(
            base_path("stubs/repository/{$repositoryStubName}.stub"),
            app_path("Repositories/{$repository}.php"),
            [
                '{{ name }}' => $name
            ]
        );
    }

    protected function makeFromStub($stubPath, $targetPath, array $replacements = [])
    {
        if (! File::exists($stubPath)) {
            $this->error("Stub not found: {$stubPath}");
            return;
        }

        // Create directory if it doesn't exist
        if (! File::exists(dirname($targetPath))) {
            File::makeDirectory(dirname($targetPath), 0755, true);
        }

        // Prevent overwriting if file already exists
        if (File::exists($targetPath)) {
            $this->warn("Skipped: {$targetPath} already exists.");
            return;
        }

        // Read stub and replace placeholders
        $content = File::get($stubPath);

        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        File::put($targetPath, $content);

        $this->info("Created: {$targetPath}");
    }
}
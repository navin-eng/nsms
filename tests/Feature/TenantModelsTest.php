<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class TenantModelsTest extends TestCase
{
    /**
     * A test to ensure all tenant models use the BelongsToTenant trait.
     *
     * @return void
     */
    public function test_tenant_models_use_belongs_to_tenant_trait()
    {
        $modelsPath = app_path('Models');
        $modelFiles = File::allFiles($modelsPath);

        $modelsMissingTrait = [];

        foreach ($modelFiles as $file) {
            $relativePath = $file->getRelativePathname();
            $className = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new \ReflectionClass($className);
            if ($reflection->isAbstract() || !$reflection->isSubclassOf(Model::class)) {
                continue;
            }

            $modelInstance = new $className();
            $tableName = $modelInstance->getTable();

            // Ignore models explicitly assigned to the provider connection
            if ($modelInstance->getConnectionName() === 'provider') {
                continue;
            }

            // Check if the table actually exists and has a school_id column
            if (Schema::connection($modelInstance->getConnectionName())->hasTable($tableName)) {
                if (Schema::connection($modelInstance->getConnectionName())->hasColumn($tableName, 'school_id')) {
                    $traits = class_uses_recursive($className);
                    if (!in_array('App\Traits\BelongsToTenant', $traits)) {
                        $modelsMissingTrait[] = $className;
                    }
                }
            }
        }

        $this->assertEmpty($modelsMissingTrait, 'The following models have a school_id column but are missing the BelongsToTenant trait: ' . implode(', ', $modelsMissingTrait));
    }
}

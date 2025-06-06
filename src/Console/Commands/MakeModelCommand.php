<?php

namespace Pluma\Console\Commands;

use Pluma\Console\Command;

/**
 * Make Model Command
 */
class MakeModelCommand extends Command
{
    /**
     * The command name
     */
    protected string $name = 'make:model';
    
    /**
     * The command description
     */
    protected string $description = 'Create a new model class';
    
    /**
     * The command arguments
     */
    protected array $arguments = [
        'name' => 'The name of the model',
    ];
    
    /**
     * The command options
     */
    protected array $options = [
        '--controller' => 'Create a controller for the model',
        '--migration' => 'Create a migration for the model',
        '--all' => 'Create a migration, factory, and controller for the model',
    ];
    
    /**
     * Execute the command
     */
    public function execute(array $args = [], array $options = []): int
    {
        // Get the model name
        $name = $args[0] ?? null;
        
        // If no name is provided, show an error
        if ($name === null) {
            $this->error('Model name is required.');
            return 1;
        }
        
        // Create the model
        $this->createModel($name, $options);
        
        // Create a controller if requested
        if (isset($options['controller']) || isset($options['all'])) {
            $this->createController($name);
        }
        
        // Create a migration if requested
        if (isset($options['migration']) || isset($options['all'])) {
            $this->createMigration($name);
        }
        
        return 0;
    }
    
    /**
     * Create a model
     */
    protected function createModel(string $name, array $options = []): void
    {
        // Get the model namespace and path
        $namespace = 'App\\Models';
        $path = PLUMA_ROOT . '/app/Models';
        
        // Create the directory if it doesn't exist
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        
        // Get the model file path
        $filePath = $path . '/' . $name . '.php';
        
        // If the file already exists, show an error
        if (file_exists($filePath)) {
            $this->error("Model {$name} already exists.");
            return;
        }
        
        // Get the model stub
        $stub = $this->getModelStub($namespace, $name);
        
        // Create the model file
        file_put_contents($filePath, $stub);
        
        // Show a success message
        $this->success("Model {$name} created successfully.");
    }
    
    /**
     * Create a controller for the model
     */
    protected function createController(string $name): void
    {
        // Create a controller command
        $command = new MakeControllerCommand();
        
        // Execute the command
        $command->execute([$name], ['--resource' => true]);
    }
    
    /**
     * Create a migration for the model
     */
    protected function createMigration(string $name): void
    {
        // Get the table name
        $table = $this->getTableName($name);
        
        // Create a migration file
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_create_{$table}_table.php";
        
        // Get the migration path
        $path = PLUMA_ROOT . '/database/migrations';
        
        // Create the directory if it doesn't exist
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        
        // Get the migration file path
        $filePath = $path . '/' . $filename;
        
        // Get the migration stub
        $stub = $this->getMigrationStub($table);
        
        // Create the migration file
        file_put_contents($filePath, $stub);
        
        // Show a success message
        $this->success("Migration created successfully.");
    }
    
    /**
     * Get the model stub
     */
    protected function getModelStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Pluma\Database\Model;

class {$name} extends Model
{
    /**
     * The table associated with the model
     */
    protected string \$table = '{$this->getTableName($name)}';
    
    /**
     * The primary key for the model
     */
    protected string \$primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable
     */
    protected array \$fillable = [];
    
    /**
     * The attributes that should be hidden for arrays
     */
    protected array \$hidden = [];
    
    /**
     * The attributes that should be cast
     */
    protected array \$casts = [];
}
PHP;
    }
    
    /**
     * Get the migration stub
     */
    protected function getMigrationStub(string $table): string
    {
        return <<<PHP
<?php

use Pluma\Database\Migration;
use Pluma\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        \$this->schema->create('{$table}', function (Blueprint \$table) {
            \$table->id();
            \$table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        \$this->schema->dropIfExists('{$table}');
    }
};
PHP;
    }
    
    /**
     * Get the table name from the model name
     */
    protected function getTableName(string $name): string
    {
        // Convert to snake_case
        $table = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        
        // Pluralize the table name
        $table = $this->pluralize($table);
        
        return $table;
    }
    
    /**
     * Pluralize a word
     */
    protected function pluralize(string $word): string
    {
        $plural = [
            '/(quiz)$/i' => '$1zes',
            '/^(ox)$/i' => '$1en',
            '/([m|l])ouse$/i' => '$1ice',
            '/(matr|vert|ind)ix|ex$/i' => '$1ices',
            '/(x|ch|ss|sh)$/i' => '$1es',
            '/([^aeiouy]|qu)y$/i' => '$1ies',
            '/(hive)$/i' => '$1s',
            '/(?:([^f])fe|([lr])f)$/i' => '$1$2ves',
            '/(shea|lea|loa|thie)f$/i' => '$1ves',
            '/sis$/i' => 'ses',
            '/([ti])um$/i' => '$1a',
            '/(tomat|potat|ech|her|vet)o$/i' => '$1oes',
            '/(bu)s$/i' => '$1ses',
            '/(alias)$/i' => '$1es',
            '/(octop)us$/i' => '$1i',
            '/(ax|test)is$/i' => '$1es',
            '/(us)$/i' => '$1es',
            '/s$/i' => 's',
            '/$/' => 's',
        ];
        
        foreach ($plural as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }
        
        return $word;
    }
}
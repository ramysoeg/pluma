<?php

namespace Pluma\Console\Commands;

use Pluma\Console\Command;

/**
 * Make Controller Command
 */
class MakeControllerCommand extends Command
{
    /**
     * The command name
     */
    protected string $name = 'make:controller';
    
    /**
     * The command description
     */
    protected string $description = 'Create a new controller class';
    
    /**
     * The command arguments
     */
    protected array $arguments = [
        'name' => 'The name of the controller',
    ];
    
    /**
     * The command options
     */
    protected array $options = [
        '--resource' => 'Create a resource controller',
        '--api' => 'Create an API controller',
    ];
    
    /**
     * Execute the command
     */
    public function execute(array $args = [], array $options = []): int
    {
        // Get the controller name
        $name = $args[0] ?? null;
        
        // If no name is provided, show an error
        if ($name === null) {
            $this->error('Controller name is required.');
            return 1;
        }
        
        // Create the controller
        $this->createController($name, $options);
        
        return 0;
    }
    
    /**
     * Create a controller
     */
    protected function createController(string $name, array $options = []): void
    {
        // Get the controller namespace and path
        $namespace = 'App\\Controllers';
        $path = PLUMA_ROOT . '/app/Controllers';
        
        // Create the directory if it doesn't exist
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        
        // Get the controller class name
        $className = $name;
        
        // If the class name doesn't end with Controller, add it
        if (!str_ends_with($className, 'Controller')) {
            $className .= 'Controller';
        }
        
        // Get the controller file path
        $filePath = $path . '/' . $className . '.php';
        
        // If the file already exists, show an error
        if (file_exists($filePath)) {
            $this->error("Controller {$className} already exists.");
            return;
        }
        
        // Get the controller stub
        if (isset($options['resource'])) {
            $stub = $this->getResourceControllerStub($namespace, $className);
        } elseif (isset($options['api'])) {
            $stub = $this->getApiControllerStub($namespace, $className);
        } else {
            $stub = $this->getControllerStub($namespace, $className);
        }
        
        // Create the controller file
        file_put_contents($filePath, $stub);
        
        // Show a success message
        $this->success("Controller {$className} created successfully.");
    }
    
    /**
     * Get the controller stub
     */
    protected function getControllerStub(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Pluma\Http\Controller;
use Pluma\Http\Request;
use Pluma\Http\Response;

class {$className} extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(): string
    {
        return \$this->view('{$this->getViewName($className)}.index');
    }
    
    /**
     * Display the specified resource
     */
    public function show(int \$id): string
    {
        return \$this->view('{$this->getViewName($className)}.show', ['id' => \$id]);
    }
}
PHP;
    }
    
    /**
     * Get the resource controller stub
     */
    protected function getResourceControllerStub(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Pluma\Http\Controller;
use Pluma\Http\Request;
use Pluma\Http\Response;

class {$className} extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(): string
    {
        return \$this->view('{$this->getViewName($className)}.index');
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(): string
    {
        return \$this->view('{$this->getViewName($className)}.create');
    }
    
    /**
     * Store a newly created resource in storage
     */
    public function store(Request \$request): never
    {
        // Validate and store the resource
        
        \$this->redirect('/{$this->getResourceName($className)}');
    }
    
    /**
     * Display the specified resource
     */
    public function show(int \$id): string
    {
        return \$this->view('{$this->getViewName($className)}.show', ['id' => \$id]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(int \$id): string
    {
        return \$this->view('{$this->getViewName($className)}.edit', ['id' => \$id]);
    }
    
    /**
     * Update the specified resource in storage
     */
    public function update(Request \$request, int \$id): never
    {
        // Validate and update the resource
        
        \$this->redirect('/{$this->getResourceName($className)}/{\$id}');
    }
    
    /**
     * Remove the specified resource from storage
     */
    public function destroy(int \$id): never
    {
        // Delete the resource
        
        \$this->redirect('/{$this->getResourceName($className)}');
    }
}
PHP;
    }
    
    /**
     * Get the API controller stub
     */
    protected function getApiControllerStub(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Pluma\Http\Controller;
use Pluma\Http\Request;
use Pluma\Http\Response;

class {$className} extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(): never
    {
        \$this->response->setHeader('Content-Type', 'application/json');
        \$this->response->send(json_encode([
            'data' => [],
        ]));
    }
    
    /**
     * Store a newly created resource in storage
     */
    public function store(Request \$request): never
    {
        // Validate and store the resource
        
        \$this->response->setHeader('Content-Type', 'application/json');
        \$this->response->send(json_encode([
            'message' => 'Resource created successfully',
            'data' => [],
        ]));
    }
    
    /**
     * Display the specified resource
     */
    public function show(int \$id): never
    {
        \$this->response->setHeader('Content-Type', 'application/json');
        \$this->response->send(json_encode([
            'data' => ['id' => \$id],
        ]));
    }
    
    /**
     * Update the specified resource in storage
     */
    public function update(Request \$request, int \$id): never
    {
        // Validate and update the resource
        
        \$this->response->setHeader('Content-Type', 'application/json');
        \$this->response->send(json_encode([
            'message' => 'Resource updated successfully',
            'data' => ['id' => \$id],
        ]));
    }
    
    /**
     * Remove the specified resource from storage
     */
    public function destroy(int \$id): never
    {
        // Delete the resource
        
        \$this->response->setHeader('Content-Type', 'application/json');
        \$this->response->send(json_encode([
            'message' => 'Resource deleted successfully',
        ]));
    }
}
PHP;
    }
    
    /**
     * Get the view name from the controller name
     */
    protected function getViewName(string $className): string
    {
        // Remove the Controller suffix
        $name = str_replace('Controller', '', $className);
        
        // Convert to snake_case
        $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        
        return $name;
    }
    
    /**
     * Get the resource name from the controller name
     */
    protected function getResourceName(string $className): string
    {
        // Remove the Controller suffix
        $name = str_replace('Controller', '', $className);
        
        // Convert to snake_case
        $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        
        // Pluralize the name
        $name = $this->pluralize($name);
        
        return $name;
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
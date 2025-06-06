<?php

namespace Pluma\Database;

/**
 * Base Model Class
 */
abstract class Model
{
    /**
     * @var Database The database instance
     */
    protected static Database $db;
    
    /**
     * @var string The table name
     */
    protected static string $table;
    
    /**
     * @var string The primary key
     */
    protected static string $primaryKey = 'id';
    
    /**
     * @var array The model attributes
     */
    protected array $attributes = [];
    
    /**
     * @var array The original attributes
     */
    protected array $original = [];
    
    /**
     * @var array The fillable attributes
     */
    protected array $fillable = [];
    
    /**
     * @var array The guarded attributes
     */
    protected array $guarded = ['id'];
    
    /**
     * Model constructor
     * 
     * @param array $attributes The model attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->original = $this->attributes;
    }
    
    /**
     * Set the database instance
     * 
     * @param Database $db The database instance
     * @return void
     */
    public static function setDatabase(Database $db): void
    {
        static::$db = $db;
    }
    
    /**
     * Get the database instance
     * 
     * @return Database The database instance
     */
    public static function getDatabase(): Database
    {
        return static::$db;
    }
    
    /**
     * Get the table name
     * 
     * @return string The table name
     */
    public static function getTable(): string
    {
        if (isset(static::$table)) {
            return static::$table;
        }
        
        $class = get_called_class();
        $parts = explode('\\', $class);
        $className = end($parts);
        
        return strtolower($className) . 's';
    }
    
    /**
     * Get the primary key
     * 
     * @return string The primary key
     */
    public static function getPrimaryKey(): string
    {
        return static::$primaryKey;
    }
    
    /**
     * Fill the model with attributes
     * 
     * @param array $attributes The attributes to fill
     * @return self
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        
        return $this;
    }
    
    /**
     * Check if an attribute is fillable
     * 
     * @param string $key The attribute key
     * @return bool Whether the attribute is fillable
     */
    protected function isFillable(string $key): bool
    {
        if (in_array($key, $this->guarded)) {
            return false;
        }
        
        return empty($this->fillable) || in_array($key, $this->fillable);
    }
    
    /**
     * Set an attribute
     * 
     * @param string $key The attribute key
     * @param mixed $value The attribute value
     * @return self
     */
    public function setAttribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;
        
        return $this;
    }
    
    /**
     * Get an attribute
     * 
     * @param string $key The attribute key
     * @return mixed The attribute value
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }
    
    /**
     * Get all attributes
     * 
     * @return array All attributes
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    
    /**
     * Magic method to get an attribute
     * 
     * @param string $key The attribute key
     * @return mixed The attribute value
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }
    
    /**
     * Magic method to set an attribute
     * 
     * @param string $key The attribute key
     * @param mixed $value The attribute value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
    
    /**
     * Magic method to check if an attribute exists
     * 
     * @param string $key The attribute key
     * @return bool Whether the attribute exists
     */
    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]);
    }
    
    /**
     * Magic method to unset an attribute
     * 
     * @param string $key The attribute key
     * @return void
     */
    public function __unset(string $key): void
    {
        unset($this->attributes[$key]);
    }
    
    /**
     * Find a model by its primary key
     * 
     * @param mixed $id The primary key value
     * @return static|null The model instance
     */
    public static function find($id): ?self
    {
        $query = "SELECT * FROM " . static::getTable() . " WHERE " . static::getPrimaryKey() . " = ? LIMIT 1";
        $result = static::$db->fetch($query, [$id]);
        
        if ($result === null) {
            return null;
        }
        
        return new static($result);
    }
    
    /**
     * Get all models
     * 
     * @return array The model instances
     */
    public static function all(): array
    {
        $query = "SELECT * FROM " . static::getTable();
        $results = static::$db->fetchAll($query);
        
        $models = [];
        
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        return $models;
    }
    
    /**
     * Create a new model
     * 
     * @param array $attributes The model attributes
     * @return static The model instance
     */
    public static function create(array $attributes): self
    {
        $model = new static($attributes);
        $model->save();
        
        return $model;
    }
    
    /**
     * Save the model
     * 
     * @return bool Whether the model was saved
     */
    public function save(): bool
    {
        if (isset($this->attributes[static::getPrimaryKey()])) {
            return $this->update();
        }
        
        return $this->insert();
    }
    
    /**
     * Insert the model
     * 
     * @return bool Whether the model was inserted
     */
    protected function insert(): bool
    {
        $table = static::getTable();
        $attributes = $this->attributes;
        
        $columns = implode(', ', array_keys($attributes));
        $placeholders = implode(', ', array_fill(0, count($attributes), '?'));
        
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $result = static::$db->execute($query, array_values($attributes));
        
        if ($result) {
            $this->attributes[static::getPrimaryKey()] = static::$db->lastInsertId();
            $this->original = $this->attributes;
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Update the model
     * 
     * @return bool Whether the model was updated
     */
    protected function update(): bool
    {
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();
        $id = $this->attributes[$primaryKey];
        
        $attributes = $this->attributes;
        unset($attributes[$primaryKey]);
        
        if (empty($attributes)) {
            return true;
        }
        
        $sets = [];
        
        foreach (array_keys($attributes) as $key) {
            $sets[] = "{$key} = ?";
        }
        
        $sets = implode(', ', $sets);
        
        $query = "UPDATE {$table} SET {$sets} WHERE {$primaryKey} = ?";
        
        $values = array_values($attributes);
        $values[] = $id;
        
        $result = static::$db->execute($query, $values);
        
        if ($result) {
            $this->original = $this->attributes;
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete the model
     * 
     * @return bool Whether the model was deleted
     */
    public function delete(): bool
    {
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();
        
        if (!isset($this->attributes[$primaryKey])) {
            return false;
        }
        
        $id = $this->attributes[$primaryKey];
        
        $query = "DELETE FROM {$table} WHERE {$primaryKey} = ?";
        
        return static::$db->execute($query, [$id]) > 0;
    }
    
    /**
     * Where clause
     * 
     * @param string $column The column name
     * @param string $operator The operator
     * @param mixed $value The value
     * @return array The model instances
     */
    public static function where(string $column, string $operator, $value = null): array
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $table = static::getTable();
        
        $query = "SELECT * FROM {$table} WHERE {$column} {$operator} ?";
        $results = static::$db->fetchAll($query, [$value]);
        
        $models = [];
        
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        return $models;
    }
    
    /**
     * First where clause
     * 
     * @param string $column The column name
     * @param string $operator The operator
     * @param mixed $value The value
     * @return static|null The model instance
     */
    public static function firstWhere(string $column, string $operator, $value = null): ?self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $table = static::getTable();
        
        $query = "SELECT * FROM {$table} WHERE {$column} {$operator} ? LIMIT 1";
        $result = static::$db->fetch($query, [$value]);
        
        if ($result === null) {
            return null;
        }
        
        return new static($result);
    }
}
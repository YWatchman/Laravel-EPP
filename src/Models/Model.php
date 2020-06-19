<?php


namespace YWatchman\LaravelEPP\Models;

abstract class Model
{
    
    /** @var array */
    protected $columns = [];
    
    /** @var array */
    protected $attributes = [];

    /**
     * Model constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($this->columns as $column) {
            if (array_key_exists($column, $attributes)) {
                $this->{$column} = $attributes[$column];
            } else {
                $this->{$column} = null;
            }
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        
        return $this->{$name};
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }
}

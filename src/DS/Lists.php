<?php
namespace Codad5\Wemall\DS;
use Closure;

class lists{
    protected array $list = [];
    protected array $backup = [];
    public function __construct(array|null $list)
    {
        $this->list = $list ?? [];
        $this->backup = $this->list;
    }
    public function push($data)
    {
        $this->list[] = $data;
    }
    public function pop()
    {
        return array_pop($this->list);
    }
    public function count()
    {
        return count($this->list);
    }

    public function first()
    {
        return $this->list[0] ?? null;
    }
    public function at(int|string $key)
    {
        return $this->list[$key];
    }
    public function to_array() : array
    {
        return $this->list;    
    }
    public function any()
    {
        return $this->list[rand(0, count($this->list) - 1)];
    }
    public function map(Closure $callback): lists
    {
        $backup = $this->list;
        $i = 0;
        foreach($backup as $key => $value){
            $backup[$key] = $callback($value, $i = null);
            $i++;
        }
        return new Lists($backup);
    }
    public function filter(Closure $closure): lists
    {
        $backup = $this->list;
        $new = [];
        $i = 0;
        foreach($backup as $key => $value){
            $value = $closure($value, $i = null);
            $value ? $new[] = $backup[$key] : null;
        }
        return new Lists($new);
    }
}
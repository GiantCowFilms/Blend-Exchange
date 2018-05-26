<?php declare(strict_types=1);

namespace BlendExchange\Client\StackExchange\Filter;

class FilterParameters
{
    private $includes;
    private $excludes;

    public function addInclude(string $include) {
        if(!in_array($include,$this->includes)){
            $this->includes[] = $include;
        }
        return $this;
    }

    public function addExclude(string $exclude) {
        if(!in_array($exclude,$this->excludes)){
            $this->excludes[] = $exclude;
        }
        return $this;
    }

    public function getIncludes() {
        return $this->includes;
    }

    public function getExcludes() {
        return $this->excludes;
    }
}
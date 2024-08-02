<?php

namespace App\Sblog\Core;

class Registry
{
    use TSingletone;
    protected static array $properties = [];

    public function setProperty($name, $value): void
    {
        self::$properties[$name] = $value;
    }

    public function getProperty($name)
    {
        if (isset(self::$properties[$name])) {
            return self::$properties[$name];
        }
        return null;
    }

     public function getProperties (): array
     {
         return self::$properties;
     }
}

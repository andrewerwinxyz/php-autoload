<?php

Autoloader::autoload()->loadClasses();

class Autoloader
{
    private static $autoload;
    private static $namespaces = [];

    private function __construct()
    {
        global $namespaces;

        $namespaces['Application\\'] = '../Application/';
        $namespaces['Framework\\'] = '../Framework/';
        $namespaces['Twig\\'] = '../Twig/';
    }

    public static function autoload(): Autoloader
    {
        if(self::$autoload === null)
        {
            self::$autoload = new Autoloader;
        }

        return self::$autoload;
    }

    public static function loadClasses(): bool
    {
        if(!spl_autoload_register('self::load'))
        {
            return false;
        }

        return true;
    }

    protected static function load(string $ns): bool
    {
        global $namespaces;
        $namespace = $ns;

        while($pos = strrpos($namespace, '\\')) 
        {
            $namespace = substr($ns, 0, $pos + 1);
            $class = substr($ns, $pos + 1);
            
            if(isset($namespaces[$namespace]))
            {                
                $file = $namespaces[$namespace].str_replace('\\', '/', $class).'.php';
    
                if(file_exists($file))
                {
                    require($file);
                    return true;
                }
            }
    
            $namespace = rtrim($namespace, '\\');
        }

        return false;
    }
}

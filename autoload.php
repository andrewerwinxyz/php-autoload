<?php
    
Autoloader::instance()->loadClasses();

class Autoloader
{
    private static $instance;
    private static $namespaces = [];

    private function __construct()
    {
        self::$namespaces = [
            'Application\\' => ['../Application/'],
            'Framework\\'   => ['../Framework/']
        ];
    }

    public static function instance(): Autoloader
    {
        if(self::$instance === null)
        {
            self::$instance = new Autoloader;
        }

        return self::$instance;
    }

    public static function loadClasses()
    {
        spl_autoload_register('self::load');
    }

    protected static function load(string $ns): bool
    {
        $namespace = $ns;

        while($pos = strrpos($namespace, '\\')) 
        {
            $namespace = substr($ns, 0, $pos + 1);
            $class = substr($ns, $pos + 1);
            
            if(isset(self::$namespaces[$namespace]))
            {
                foreach(self::$namespaces[$namespace] as $dir) 
                {
                    $file = $dir.str_replace('\\', '/', $class).'.php';
        
                    if(file_exists($file))
                    {
                        require($file);
                        return true;
                    }
                }
            }
    
            $namespace = rtrim($namespace, '\\');
        }

        return false;
    }
}
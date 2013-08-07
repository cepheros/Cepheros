<?php

/**
 * Autoloader personalizado para a API Client.
 *
 * @author Tiago
 * @since 05/05/2011
 * @version 1.0
 */
class HumanAutoloader
{
    public static function register()
    {
        return spl_autoload_register(array('HumanAutoloader', 'load'));
    }

    public static function load($pObjectName)
    {
        if ((class_exists($pObjectName)) || (strpos($pObjectName, 'Human') === False)) {
            return false;
        }
        
        $filePhp = $pObjectName . '.php';
        
        return self::mapperClass(HUMAN_ROOT, $filePhp);
    }
    
    private static function mapperClass($dir, $file)
    {
        $pObjectFilePath = $dir . DIRECTORY_SEPARATOR . $file;
        
        if ((file_exists($pObjectFilePath) === false) || (is_readable($pObjectFilePath) === false)) {
            $itemHandler = opendir($dir);
            
            while (($item = readdir($itemHandler)) !== false) {
                if (trim($item) != "." && trim($item) != "..") {
                    $dirTmp = $dir . DIRECTORY_SEPARATOR . $item;
                    
                    if (is_dir($dirTmp) && substr($item, 0, 1) != ".") {
                        if (self::mapperClass($dirTmp, $file)) {
                            return true;
                        }
                    }
                }
            }
            
            return false;
        } else {
            require($pObjectFilePath);
            return true;
        }
    }
}
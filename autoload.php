<?php
function autoload($class_name)
{
    $class_name = ltrim($class_name, '\\');
    $file_name = 'classes/';
    $namespace = '';
    if ($last_namespace_pos = strrpos($class_name, '\\')) {
        $namespace = substr($class_name, 0, $last_namespace_pos);
        $class_name = substr($class_name, $last_namespace_pos + 1);
        $file_name .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $file_name .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

    require $file_name;
}

spl_autoload_register('autoload');

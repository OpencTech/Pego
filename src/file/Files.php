<?php

namespace Pego\file;

use Composer\InstalledVersions;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class Files {

    function getProjectClassList(string | array | null $extends = null, bool $isAbstract = false): array
    {
        ['install_path' => $install_path] = InstalledVersions::getRootPackage();
        $classLoader = require "{$install_path}/vendor/autoload.php";
        $psr4 = $classLoader->getPrefixesPsr4();

        $options = (object)['extends' => $extends, 'isAbstract' => $isAbstract];

        $result = [];
        foreach ($psr4 as $namespace => $path) {
            $result = array_merge($result, $this->findInSpace($path[0], $namespace, $options));
        }

        return $result;
    }


    private function findInSpace(string $path, string $namespace, object $options): array {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        $abstractClasses = [];

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = $file->getPathname();
                [$class, $className, $classNamespace] = $this->getClassName($path, $namespace, $filePath);

                try {
                    $reflectionClass = new ReflectionClass($class);

                    $isAbstract = $options->isAbstract ? $reflectionClass->isAbstract() : true;
                    $isExtends  = $options->extends ? $this->inExtends($reflectionClass, $options->extends) : true;

                    if ($isAbstract && $isExtends)
                        $abstractClasses[] = new ClassInstance(
                            $class,
                            $className,
                            $classNamespace,
                            $path,
                            $filePath,
                        );
                } catch (\Throwable $th) {
                    echo "### Не удалось создать - new ReflectionClass($class)\n";
                }

            }
        }
    
        return $abstractClasses;
    }


    private function inExtends(ReflectionClass $reflectionClass, array | string $extends): bool {
        foreach ((array)$extends as $class) {
            if ($reflectionClass->isSubclassOf((string)$class))
                return true;
        }

        return false;
    }

    private function getClassName(string $path, string $namespace, string $classPath): array
    {
        $classPath = substr($classPath, strlen($path) +1, -4);
        $ar = explode(DIRECTORY_SEPARATOR, $classPath);
        $className = end($ar);
        $namespace = trim($namespace, '\\');
        $class = implode('\\', [...explode('\\', $namespace), ...$ar]);

        return [$class, $className, $namespace];
    }
}
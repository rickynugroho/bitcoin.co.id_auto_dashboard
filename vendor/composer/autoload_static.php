<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd962fd3efe15d3172a3362acc0dff7d7
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Filebase\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Filebase\\' => 
        array (
            0 => __DIR__ . '/..' . '/tmarois/filebase/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Detection' => 
            array (
                0 => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/namespaced',
            ),
        ),
    );

    public static $classMap = array (
        'Mobile_Detect' => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/Mobile_Detect.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd962fd3efe15d3172a3362acc0dff7d7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd962fd3efe15d3172a3362acc0dff7d7::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitd962fd3efe15d3172a3362acc0dff7d7::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitd962fd3efe15d3172a3362acc0dff7d7::$classMap;

        }, null, ClassLoader::class);
    }
}

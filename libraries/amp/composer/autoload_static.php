<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1079f4a7e0414ee7c5974666cd09ad1b
{
    public static $files = array (
        'e8aa6e4b5a1db2f56ae794f1505391a8' => __DIR__ . '/..' . '/amphp/amp/lib/functions.php',
        '76cd0796156622033397994f25b0d8fc' => __DIR__ . '/..' . '/amphp/amp/lib/Internal/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Amp\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Amp\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/amp/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1079f4a7e0414ee7c5974666cd09ad1b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1079f4a7e0414ee7c5974666cd09ad1b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

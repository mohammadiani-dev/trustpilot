<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit512a2f328158cc625078af03b0691283
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Mohammadiani\\TrustPilot\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Mohammadiani\\TrustPilot\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit512a2f328158cc625078af03b0691283::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit512a2f328158cc625078af03b0691283::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit512a2f328158cc625078af03b0691283::$classMap;

        }, null, ClassLoader::class);
    }
}
<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit31edb896ef2f814c97fe7cd0326e5c6f
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Carbon_Fields\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Carbon_Fields\\' => 
        array (
            0 => __DIR__ . '/..' . '/htmlburger/carbon-fields/core',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit31edb896ef2f814c97fe7cd0326e5c6f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit31edb896ef2f814c97fe7cd0326e5c6f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

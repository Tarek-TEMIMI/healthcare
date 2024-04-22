<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit110a170eb7f7c413d002a41975a45629
{
    public static $files = array (
        '78684bf02183e16ed86099084edb2d20' => __DIR__ . '/../..' . '/includes/Utils/helpers.php',
        'd1834d48b8156b6793614fa0c1a79843' => __DIR__ . '/../..' . '/includes/Utils/ajax_helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Iqonic\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Iqonic\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit110a170eb7f7c413d002a41975a45629::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit110a170eb7f7c413d002a41975a45629::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
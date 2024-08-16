<?php

namespace GrimPirate\Halberd\Config;

class Registrar
{
    // Classes for Halberd module
    public static function Auth(): array
    {
        return [
            'actions' => [
                'register' => \GrimPirate\Halberd\Authentication\Actions\Register::class,
                'login'    => \GrimPirate\Halberd\Authentication\Actions\Login::class,
            ],
        ];
    }
}

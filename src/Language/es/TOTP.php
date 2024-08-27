<?php

return [
    // 2FA
    'title2FA'    => 'Verificación de Doble Factor',
    'confirmCode' => 'Ingrese el código de 6 dígitos de su aplicación.',

    // Register
    'googleApp'  => 'Escanea este código QR utilizando una aplicación de verificación de doble factor (<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=es" target="_blank">Android</a>/<a href="https://apps.apple.com/es/app/google-authenticator/id388497605" target="_blank">iOS</a>) e ingrese una contraseña única (OTP) para activar su cuenta.',
    'problems'  => '¿No puede escanear? Ingrese la clave de configuración manualmente <strong>{placeholder}</strong> en su aplicación.',

    // Spark commands
    'spark' => [
        'initialize' => [
            'input' => [
                'issuer' => '¿Emisor?',
                'stylesheet' => 'CSS?',
            ],
            'description' => 'Inicializa los parámetros de configuración para Halberd.',
            'usage' => 'halberd:ini <emisor> <css>',
            'arguments' => [
                'issuer' => 'Emisor de la contraseña única (OTP)',
                'stylesheet' => 'Ubicación de la hoja de estilos CSS para los formularios de activación/autenticación',
            ],
        ],
        'totp' => [
            'input' => [
                'id' => 'ID?',
            ],
            'description' => 'Sobrescribe una identidad TOTP para un usuario particular.',
            'usage' => 'halberd:totp <id>',
            'arguments' => [
                'id' => 'ID del usuario',
            ],
        ],
    ],
];

<?php

namespace GrimPirate\Halberd\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Halberd extends BaseCommand
{
    protected $group =          'Halberd';
    protected $name =           'halberd:init';
    protected $description =    'Initializes configuration parameters for Halberd.';
    protected $usage =          'halberd:init <issuer>';
    protected $arguments = [
        'issuer' => 'The One-Time Password (OTP) issuer',
    ];

    public function run(array $params)
    {
        service('settings')->set('Halberd.issuer', empty($params) ? CLI::prompt('Issuer?', 'Halberd', 'required') : $params[0]);

        service('settings')->set('Auth.views', [
            'login'                       => '\CodeIgniter\Shield\Views\login',
            'register'                    => '\CodeIgniter\Shield\Views\register',
            'layout'                      => '\GrimPirate\Halberd\Views\layout', // Replaces '\CodeIgniter\Shield\Views\layout'
            'action_email_2fa'            => '\CodeIgniter\Shield\Views\email_2fa_show',
            'action_email_2fa_verify'     => '\CodeIgniter\Shield\Views\email_2fa_verify',
            'action_email_2fa_email'      => '\CodeIgniter\Shield\Views\Email\email_2fa_email',
            'action_email_activate_show'  => '\CodeIgniter\Shield\Views\email_activate_show',
            'action_email_activate_email' => '\CodeIgniter\Shield\Views\Email\email_activate_email',
            'action_halberd_register'     => '\GrimPirate\Halberd\Views\register',  // New view
            'action_halberd_login'        => '\GrimPirate\Halberd\Views\login', // New view
            'magic-link-login'            => '\CodeIgniter\Shield\Views\magic_link_form',
            'magic-link-message'          => '\CodeIgniter\Shield\Views\magic_link_message',
            'magic-link-email'            => '\CodeIgniter\Shield\Views\Email\magic_link_email',
        ]);

        service('settings')->set('Auth.actions', [
            'register' => '\GrimPirate\Halberd\Authentication\Actions\Register',
            'login' => '\GrimPirate\Halberd\Authentication\Actions\Login',
        ]);
    }
}
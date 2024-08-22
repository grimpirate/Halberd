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
        helper('setting');

        setting('Halberd.issuer', empty($params) ? CLI::prompt('Issuer?', setting('Halberd.issuer'), 'required') : $params[0]);

        $views = setting('Auth.views');

        $views['action_halberd_layout'] = '\GrimPirate\Halberd\Views\layout';
        $views['action_halberd_register'] = '\GrimPirate\Halberd\Views\register';
        $views['action_halberd_login'] = '\GrimPirate\Halberd\Views\login';

        setting('Auth.views', $views);

        $actions = setting('Auth.actions');

        $actions['register'] = '\GrimPirate\Halberd\Authentication\Actions\Register';
        $actions['login'] = '\GrimPirate\Halberd\Authentication\Actions\Login';

        setting('Auth.actions', $actions);
    }
}

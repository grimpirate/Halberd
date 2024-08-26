<?php

namespace GrimPirate\Halberd\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use Psr\Log\LoggerInterface;

class Halberd extends BaseCommand
{
    protected $group =          'Halberd';
    protected $name =           'halberd:ini';

    public function __construct(LoggerInterface $logger, Commands $commands)
    {
        parent::__construct($logger, $commands);
        $this->description   = lang('TOTP.prompt.description');
        $this->usage = lang('TOTP.prompt.usage');
        $this->arguments = [
            'issuer' => lang('TOTP.prompt.arguments.issuer'),
        ];
    }

    public function run(array $params)
    {
        helper('setting');

        setting('TOTP.issuer', empty($params) ? CLI::prompt(lang('TOTP.prompt.input'), setting('TOTP.issuer') ?? 'Halberd', 'required') : $params[0]);

        $views = setting('Auth.views');

        $views['action_totp'] = '\GrimPirate\Halberd\Views\layout';

        setting('Auth.views', $views);

        $actions = setting('Auth.actions');

        $actions['register'] = '\GrimPirate\Halberd\Authentication\Actions\TOTP2FA';
        $actions['login'] = '\GrimPirate\Halberd\Authentication\Actions\TOTP2FA';

        setting('Auth.actions', $actions);
    }
}

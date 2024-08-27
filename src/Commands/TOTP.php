<?php

namespace GrimPirate\Halberd\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use Psr\Log\LoggerInterface;

use CodeIgniter\Config\Factories;
use CodeIgniter\Shield\Models\UserIdentityModel;
use GrimPirate\Halberd\Authentication\Authenticators\TOTP as OTP;

class TOTP extends BaseCommand
{
    protected $group =          'Halberd';
    protected $name =           'halberd:totp';

    public function __construct(LoggerInterface $logger, Commands $commands)
    {
        parent::__construct($logger, $commands);
        $this->description   = lang('TOTP.prompt.totp.description');
        $this->usage = lang('TOTP.prompt.totp.usage');
        $this->arguments = [
            'id' => lang('TOTP.prompt.totp.arguments.id'),
        ];
    }

    public function run(array $params)
    {
        helper('setting');

        $user = auth()->getProvider()->findById(!isset($params[0]) ? CLI::prompt(lang('TOTP.prompt.totp.input'), null, 'required') : $params[0]);

		$actionClass = service('settings')->get('Auth.actions')['register'];

		$action = Factories::actions($actionClass);

		$identityModel = model(UserIdentityModel::class);

		$identityModel->deleteIdentitiesByType($user, OTP::ID_TYPE_TOTP_2FA);

		$action->createIdentity($user);
    }
}
<?php

declare(strict_types=1);

namespace GrimPirate\Halberd\Authentication\Authenticators;

use CodeIgniter\Shield\Authentication\Authenticators\Session;

use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Entities\UserIdentity;
use CodeIgniter\Shield\Exceptions\LogicException;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Models\UserModel;

class TOTP extends Session
{
    // Identity types
    public const ID_TYPE_TOTP_2FA = 'totp_2fa';

    /**
     * Check token in Action
     *
     * @param string $token Token to check
     */
    public function checkAction(UserIdentity $identity, string $token): bool
    {
        $user = ($this->loggedIn() || $this->isPending()) ? $this->user : null;

        if ($user === null) {
            throw new LogicException('Cannot get the User.');
        }

        helper('totp2fa');

        if (
            $token === '' || 
            ! verifyKeyNewer($identity->secret, $token, $identity->last_used_at->getTimestamp())
        ) {
            return false;
        }

        // On success - update last_used_at
        $this->userIdentityModel->touchIdentity($identity);

        // Clean up our session
        $this->removeSessionUserKey('auth_action');
        $this->removeSessionUserKey('auth_action_message');

        $this->user = $user;

        $this->completeLogin($user);

        return true;
    }
}
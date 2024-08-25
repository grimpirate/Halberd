<?php

declare(strict_types=1);

namespace GrimPirate\Halberd\Authentication\Actions;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Entities\UserIdentity;
use CodeIgniter\Shield\Exceptions\LogicException;
use CodeIgniter\Shield\Exceptions\RuntimeException;
use CodeIgniter\Shield\Models\UserIdentityModel;

use CodeIgniter\Shield\Authentication\Actions\ActionInterface;

class Halberd implements ActionInterface
{
    private const REGISTER = 'register';
    private const LOGIN = 'login';
    private const TYPE = 'google_2fa';

    /**
     * Shows the initial screen to the user with a QR code for activation
     */
    public function show(): string
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $user = $authenticator->getPendingUser();
        if ($user === null)
            throw new RuntimeException('Cannot get the pending login User.');

        $identity = $this->getIdentity($user);

        $register = $identity->extra === self::REGISTER;

        return view(service('settings')->get('Auth.views')['action_halberd'], $register ? ['qrcode' => $identity->secret2, 'secret' => $identity->secret] : []);
    }

    /**
     * This method is unused.
     *
     * @return Response|string
     */
    public function handle(IncomingRequest $request)
    {
        throw new PageNotFoundException();
    }

    /**
     * Verifies the QR code matches an
     * identity we have for that user.
     *
     * @return RedirectResponse|string
     */
    public function verify(IncomingRequest $request)
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $postedToken = $request->getVar('token');

        $user = $authenticator->getPendingUser();
        if ($user === null)
            throw new RuntimeException('Cannot get the pending login User.');

        helper('google2fa');
        $identity = $this->getIdentity($user);
        $secret = $identity->secret;
        $register = $identity->extra === self::REGISTER;
        $identity->secret = getCurrentOtp($secret);

        // No match - let them try again.
        if (
            ! verifyKeyNewer($secret, $postedToken, $identity->last_used_at->getTimestamp()) ||
            ! $authenticator->checkAction($identity, $postedToken)
        ) {
            session()->setFlashdata('error', lang($register ? 'Auth.invalidActivateToken' : 'Auth.invalid2FAToken'));

            return view(service('settings')->get('Auth.views')['action_halberd'], $register ? ['qrcode' => $identity->secret2, 'secret' => $secret] : []);
        }

        $user = $authenticator->getUser();

        if($user->isNotActivated())
            $user->activate();

        $this->generateIdentity(
            $user,
            [
                'type'  => self::TYPE,
                'extra'  => self::LOGIN,
                'secret2' => $identity->secret2,
                'last_used_at' => Time::now(),
            ],
            static fn (): string => $secret,
            true
        );

        // Success!
        return $register ? 
            redirect()->to(config('Auth')->registerRedirect())
            ->with('message', lang('Auth.registerSuccess'))
            : redirect()->to(config('Auth')->loginRedirect());
    }

    /**
     * Creates an identity for the action of the user.
     *
     * @return string secret
     */
    public function createIdentity(User $user): string
    {
        $identity = $this->getIdentity($user);
        if(null !== $identity)
            return $identity->secret;

        helper('google2fa');
        $secret = generateSecretKey();
        return $this->generateIdentity(
            $user,
            [
                'type'  => self::TYPE,
                'extra'  => self::REGISTER,
                'secret2' => qrcode(service('settings')->get('Halberd.issuer'), $user->username ?? $user->email, $secret),
                'last_used_at' => Time::yesterday(),
            ],
            static fn (): string => $secret,
            true
        );
    }

    /**
     * Creates an identity for the action of the user.
     *
     * @return string secret
     */
    private function generateIdentity(User $user, array $data, callable $generator, bool $deletePriors): string
    {
        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        // Delete any previous identities for action
        if($deletePriors)
            $identityModel->deleteIdentitiesByType($user, $data['type']);

        return $identityModel->createCodeIdentity($user, $data, $generator);
    }

    /**
     * Returns an identity for the action of the user.
     */
    private function getIdentity(User $user): ?UserIdentity
    {
        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        return $identityModel->getIdentityByType(
            $user,
            self::TYPE
        );
    }

    /**
     * Returns the string type of the action class.
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}

<?php

namespace DemoBundle\Service;

use Demo\Enum\TokenType;
use DemoBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CookieService
 * @package DemoBundle\Service
 */
class CookieService
{
    const COOKIENAME = 'demo';

    /**
     * @var TokenService
     */
    private $tokenService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * CookieService constructor.
     *
     * @param TokenService $tokenService
     * @param UserService $userService
     * @param SessionService $sessionService
     */
    public function __construct(
        TokenService $tokenService,
        UserService $userService,
        SessionService $sessionService
    ) {
        $this->tokenService = $tokenService;
        $this->userService = $userService;
        $this->sessionService = $sessionService;
    }

    /**
     * Create a new user Cookie
     * with a token as value
     */
    public function setLoginCookie(User $user)
    {
        $token = $this->tokenService->add(
            [
                'userId' => $user->getId(),
                'type' => TokenType::TYPE_LOGIN
            ]
        );

        // just leave it for a year
        $expire = time() + (86400 * 365);

        // set the cookie
        setcookie(self::COOKIENAME, $token->getToken(), $expire, '/', null, true, true);
    }

    /**
     *
     */
    public function validateLoginCookie()
    {
        // check cookie presence and if there is an active session
        // if there is no active session, create one
        // after that, remove the token and create a new token + cookie
        if (!empty($_COOKIE[CookieService::COOKIENAME]) && empty($this->sessionService->getSession())) {
            $token = $this->fetchToken();

            if ($token === false) {
                $this->removeCookie();
                return;
            }

            $user = $this->userService->getById($token->getUserId());
            $this->sessionService->createSession($user);
            $this->tokenService->remove($token);
            $this->removeCookie();
            $this->setLoginCookie($user);
        }
    }

    public function removeCookie()
    {
        setcookie(self::COOKIENAME, null, null, '/', null, true, true);
    }

    private function fetchToken()
    {
        try {
            return $this->tokenService->findOne(
                [
                    'token' => $_COOKIE[CookieService::COOKIENAME],
                    'type' => TokenType::TYPE_LOGIN
                ]
            );
        } catch (NotFoundHttpException $e) {
            return false;
        }
    }
}

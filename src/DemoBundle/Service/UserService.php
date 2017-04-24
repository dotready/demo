<?php

namespace DemoBundle\Service;

use Demo\Enum\GameStatus;
use DemoBundle\Exception\SessionExpiredException;
use DemoBundle\Exception\UserNotFoundException;
use DemoBundle\Exception\ValidationException;
use Demo\Entity\UserInterface;
use Demo\Repository\UserRepositoryInterface;
use DemoBundle\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class UserService
 * @package DemoBundle\Service
 */
class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $repository
     * @param RequestStack $requestStack
     * @param SessionService $sessionService
     */
    public function __construct(
        UserRepositoryInterface $repository,
        RequestStack $requestStack,
        SessionService $sessionService
    ) {
        $this->repository = $repository;
        $this->requestStack = $requestStack;
        $this->sessionService = $sessionService;
    }

    /**
     * @param User $user
     *
     * @return UserInterface
     *
     * @throws ValidationException
     */
    public function add(User $user) : UserInterface
    {
        return $this->repository->add($user);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function save(UserInterface $user) : UserInterface
    {
        $user = $this->repository->update($user);
        $this->sessionService->refreshSessionUser($user);
        return $user;
    }

    /**
     * @param string $email
     * @throws UserNotFoundException
     * @return UserInterface
     */
    public function getByEmail(string $email) : UserInterface
    {
        $user = $this->repository->get(['email' => $email]);

        if ($user === null) {
            throw new UserNotFoundException([$email]);
        }

        return $user;
    }

    /**
     * @param string $id
     * @return UserInterface
     * @throws UserNotFoundException
     */
    public function getById(string $id)
    {
        $user = $this->repository->get(['id' => $id]);

        if ($user === null) {
            throw new UserNotFoundException([$id]);
        }

        return $user;
    }

    public function getActiveUser()
    {
        $session = new Session();
        $user = $session->get('user');

        if ($user === null) {
            $request = $this->requestStack->getCurrentRequest();
            $session->set('current_url', $request->getUri());
            throw new SessionExpiredException();
        }

        return unserialize($user);
    }

    /**
     * @param array $criteria
     * @param array $sort
     * @param null $limit
     */
    public function find(array $criteria, array $sort = array(), $limit = null)
    {
        return $this->repository->find($criteria, $sort, $limit);
    }
}

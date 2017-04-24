<?php

namespace DemoBundle\Repository;

use Demo\Entity\UserInterface;
use Demo\Repository\UserRepositoryInterface;
use DemoBundle\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Class UserRepository
 * @package DemoBundle\Repository
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function add(UserInterface $user) : UserInterface
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param array $criteria
     * @return UserInterface
     */
    public function get(array $criteria)
    {
        $repository = $this->entityManager->getRepository(User::class);
        return $repository->findOneBy($criteria);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function update(UserInterface $user) : UserInterface
    {
        $this->entityManager->merge($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function delete(UserInterface $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @param array $criteria
     * @param array $sort
     * @param null $limit
     * @return array
     */
    public function find(array $criteria, array $sort = array(), $limit = null)
    {
        $repository = $this->entityManager->getRepository(User::class);
        return $repository->findBy($criteria, $sort, $limit);
    }
}

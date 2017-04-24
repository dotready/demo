<?php

namespace DemoBundle\Repository;

use Demo\Entity\TokenInterface;
use Demo\Repository\TokenRepositoryInterface;
use DemoBundle\Entity\Token;
use Doctrine\ORM\EntityManager;

/**
 * Class TokenRepository
 * @package DemoBundle\Repository
 */
class TokenRepository implements TokenRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * TokenRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function add(TokenInterface $token) : TokenInterface
    {
        $this->entityManager->persist($token);
        $this->entityManager->flush();
        return $token;
    }

    /**
     * @param array $criteria
     * @return TokenInterface
     */
    public function get(array $criteria)
    {
        $repository = $this->entityManager->getRepository(Token::class);
        return $repository->findOneBy($criteria);
    }

    /**
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function update(TokenInterface $token) : TokenInterface
    {
        $this->entityManager->merge($token);
        $this->entityManager->flush();
        return $token;
    }

    /**
     * @param TokenInterface $token
     * @return void
     */
    public function remove(TokenInterface $token)
    {
        $this->entityManager->remove($token);
        $this->entityManager->flush();
    }

    public function findOne(array $criteria)
    {
        $repository = $this->entityManager->getRepository(Token::class);
        return $repository->findOneBy($criteria);
    }
}

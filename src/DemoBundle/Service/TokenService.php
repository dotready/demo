<?php

namespace DemoBundle\Service;

use Demo\Entity\TokenInterface;
use Demo\Repository\TokenRepositoryInterface;
use DemoBundle\Entity\Token;
use DemoBundle\Exception\ValidationException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TokenService
 * @package DemoBundle\Service
 */
class TokenService
{
    /**
     * @var TokenRepositoryInterface
     */
    private $repository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * TokenService constructor.
     * @param TokenRepositoryInterface $repository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        TokenRepositoryInterface $repository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->validator  = $validator;
    }

    /**
     * @param string $id
     * @return TokenInterface
     * @throws NotFoundHttpException
     */
    public function getById(string $id)
    {
        $token = $this->repository->get(['id' => $id]);

        if ($token === null) {
            throw new NotFoundHttpException('Token not found');
        }

        return $token;
    }

    /**
     * @param array $criteria
     * @return mixed
     */
    public function findOne(array $criteria)
    {
        $token = $this->repository->findOne($criteria);

        if ($token === null) {
            throw new NotFoundHttpException('Invalid token');
        }

        return $token;
    }

    /**
     * @param array $post
     * @return TokenInterface
     * @throws ValidationException
     */
    public function add(array $post) : TokenInterface
    {
        $token  = $this->serializer->deserialize(json_encode($post), Token::class, 'json');

        $errors = $this->validator->validate($token);

        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        return $this->repository->add($token);
    }

    /**
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function save(TokenInterface $token) : TokenInterface
    {
        return $this->repository->update($token);
    }

    /**
     * @param Token $token
     */
    public function remove(Token $token)
    {
        $this->repository->remove($token);
    }
}

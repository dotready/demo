<?php

namespace DemoBundle\Converter;

use DemoBundle\Exception\ValidationException;
use DemoBundle\Service\UserService;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestParamConverter.
 */
class RequestParamConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * SerializedParamConverter constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration)
    {
        $options = $configuration->getOptions();

        return true;
    }

    /**
     * This method will validate for valid User,
     * construct and validate entity,
     * pass the created entity through controller
     *
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @throws ValidationException
     * @throws AccessDeniedHttpException
     *
     * @return bool
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if ($request->isXmlHttpRequest() === false) {
            $requestJson = json_encode($request->request->all());
        } else {
            $requestJson = $request->getContent();
            $requestJson = $this->validateJson($requestJson);
        }

        $class = $configuration->getClass();

        $object = $this->serializer->deserialize(
            $requestJson,
            $class,
            'json'
        );

        // deserialize and validate
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // set the object as the request attribute with the given name
        // (this will later be an argument for the action)
        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    /**
     * Validate if the content sent
     * is in correct JSON format
     *
     * @param string $content
     *
     * @throws BadRequestHttpException
     *
     * @return mixed
     */
    private function validateJson(string $content)
    {
        $json = @json_decode($content);

        if ($json === null) {
            throw new BadRequestHttpException('Bad JSON');
        }

        return $json;
    }
}

<?php

namespace DemoBundle\Controller;

use DemoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    /**
     * @ParamConverter(converter="entity_converter", name="user")
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(User $user)
    {
        $this->get('user.service')->save($user);

        return new RedirectResponse($this->generateUrl('adminUserOverview'));
    }

    /**
     * @ParamConverter("user")
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(User $user)
    {
        return $this->render(
            'AtusAdminBundle:User:edit.html.twig',
            array('user' => $user)
        );
    }
}

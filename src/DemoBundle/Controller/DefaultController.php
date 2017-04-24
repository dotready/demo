<?php

namespace DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cookieInformationAction()
    {
        return $this->render('DemoBundle:Index:cookies.html.twig', []);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function mailItemAction(Request $request)
    {
        $user = $this->get('user.service')->getActiveUser();

        $files = [];

        foreach ($request->files as $fileBag) {
            foreach ($fileBag as $file) {
                if ($file !== null) {
                    $move = $file->move('/tmp/uploads', 'foo.jpg');
                    $files[] = '/tmp/uploads/foo.jpg';
                }
            }
        }

        return new RedirectResponse($this->generateUrl('addUserItemSuccess'));
    }
}

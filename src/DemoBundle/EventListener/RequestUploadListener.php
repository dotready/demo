<?php

namespace DemoBundle\EventListener;

use Demo\Enum\Role;
use DemoBundle\Exception\SessionExpiredException;
use DemoBundle\Service\ImageService;
use DemoBundle\Service\UserService;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestUploadListener
 * @package DemoBundle\EventListener
 */
class RequestUploadListener
{
    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * RequestUploadListener constructor.
     * @param ImageService $imageService
     * @param UserService $userService
     */
    public function __construct(ImageService $imageService, UserService $userService)
    {
        $this->imageService = $imageService;
        $this->userService = $userService;
    }

    /**
     *
     * Pretend to be a 404 if something
     * is wrong.
     */
    private function validateRequester()
    {
        $session = new Session();
        $user = unserialize($session->get('user'));

        if (empty($user)) {
            return false;
        }

        return true;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        if ($event->getRequest()->isMethod('get')) {
            // nothind to do here
            return;
        }

        if (!$this->validateRequester()) {
            return;
        }

        $request = $event->getRequest();
        $xHeaderStore = $request->headers->get('X-Document-Store');

        // handle regular _FILES
        if (count($request->files) > 0) {
            $files = $this->store($this->stack($request->files));
            $images = [];
            if ($xHeaderStore !== 'no') {
                foreach ($files as $file) {
                    $title = !empty($request->get('title')) ? $request->get('title') : '';
                    $image = $this->imageService->create([
                        'location' => $file->getFilename(),
                        'category' => $request->get('category'),
                        'title' => $title
                    ]);
                    $images[] = $image;
                }
            }

            $request->attributes->set('files', $files);
            $request->attributes->set('images', $images);
        }
    }

    /**
     * @param array $files
     * @todo get path from config
     *
     * @return array
     */
    private function store(array $files)
    {
        $splFiles = [];

        $path = realpath(__DIR__ . '/../../../') . '/shared/files';

        foreach ($files as $file) {
            if ($file !== null) {
                $extension = $file->getClientOriginalExtension();
                $tmpname = uniqid() . '.' . $extension;
                $splFile = $file->move($path, $tmpname);
                $splFiles[] = $splFile;
            }
        }

        return $splFiles;
    }

    /**
     * Arrange fileBag to one
     * iterable stack/array
     *
     * @param FileBag $fileBag
     * @return UploadedFile[]
     */
    private function stack(FileBag $fileBag) : array
    {
        $files = [];

        foreach ($fileBag->all() as $file) {
            if (is_array($file)) {
                foreach ($file as $f) {
                    $files[] = $f;
                }
            } else {
                $files[] = $file;
            }
        }

        return $files;
    }
}

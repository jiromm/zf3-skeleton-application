<?php

namespace Album\Controller;

use Album\Service\AlbumService;
use Settings\Common\CommonActionController;
use Zend\View\Model\ViewModel;

class IndexController extends CommonActionController
{
    public function indexAction()
    {
        /**
         * @var AlbumService $albumService
         */
        $albumService = $this->container->get('Album\Service\AlbumService');

//        $albumService->insertAlbum();
//        $albumService->updateAlbum();

        $albums = $albumService->getAlbums();

        return new ViewModel([
            'albums' => $albums,
        ]);
    }
}

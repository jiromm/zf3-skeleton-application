<?php

namespace Demo\Controller;

use Demo\Service\AlbumService;
use Demo\Service\TestService;
use Settings\Common\CommonActionController;
use Zend\View\Model\JsonModel;

class IndexController extends CommonActionController
{
    public function indexAction()
    {
        /**
         * @var AlbumService $albumService
         */
        $albumService = $this->container->get('Demo\Service\AlbumService');

        $albums = $albumService->getAlbums();

        if ($albums->count()) {
            foreach ($albums as $album) {
                echo $album->getId() . ') ' . $album->getArtist() . ', ' . $album->getTitle() . '<br>';
            }
        }

        exit;

        return new JsonModel([
            'name' => 'Aram Baghdasaryan',
            'multiplier' => [
                '5 * 6 equal to' => $titanusService->multiplyThings(5, 6),
            ],
        ]);
    }
}

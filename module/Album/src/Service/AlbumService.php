<?php

namespace Album\Service;

use Album\Entity\AlbumEntity;
use Album\Mapper\AlbumMapper;
use Settings\Common\CommonService;
use Zend\Db\ResultSet\ResultSet;

class AlbumService extends CommonService
{
    /**
     * @return AlbumEntity[]|ResultSet
     */
    public function getAlbums()
    {
        /**
         * @var AlbumMapper $albumMapper
         */
        $albumMapper = $this->container->get('Album\Mapper\AlbumMapper');

        $albums = $albumMapper->getAlbums();

        return array_map(function (AlbumEntity $album) {
            return $album->exchangeArray();
        }, $albums);
    }
}

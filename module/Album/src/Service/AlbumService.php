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

        return $albums->toArray();
    }

    /**
     * @return void
     */
    public function updateAlbum()
    {
        /**
         * @var AlbumMapper $albumMapper
         */
        $albumMapper = $this->container->get('Album\Mapper\AlbumMapper');

        $albumEntity = new AlbumEntity();
        $albumEntity->setArtist('Random Artist 2');

        $albumMapper->update($albumEntity, ['id' => 1]);
    }

    /**
     * @return void
     */
    public function insertAlbum()
    {
        /**
         * @var AlbumMapper $albumMapper
         */
        $albumMapper = $this->container->get('Album\Mapper\AlbumMapper');

        $albumEntity = new AlbumEntity();
        $albumEntity->setArtist('Random Artist 3');

        $albumMapper->insert($albumEntity);
    }
}

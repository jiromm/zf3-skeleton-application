<?php

namespace Demo\Service;

use Demo\Entity\AlbumEntity;
use Demo\Mapper\AlbumMapper;
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
        $albumMapper = $this->container->get('Demo\Mapper\AlbumMapper');

        return $albumMapper->getAlbums();
    }
}

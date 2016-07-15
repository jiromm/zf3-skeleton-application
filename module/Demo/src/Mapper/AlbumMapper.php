<?php

namespace Demo\Mapper;

use Demo\Entity\AlbumEntity;
use Settings\Common\CommonTableGateway;
use Settings\Library\DBTables;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;

class AlbumMapper extends CommonTableGateway
{
    protected $tableName = DBTables::TBL_ALBUM;

    /**
     * @return ResultSet|AlbumEntity[]
     */
    public function getAlbums()
    {
        return $this->fetchAll(function (Select $select) {
            $select->where->greaterThan('id', 2);
        });
    }
}

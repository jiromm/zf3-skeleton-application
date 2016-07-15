<?php

namespace Demo\Service;

use Settings\Common\CommonService;

class TestService extends CommonService
{
    public function multiplyThings($operator1, $operator2)
    {
        return $operator1 * $operator2;
    }
}

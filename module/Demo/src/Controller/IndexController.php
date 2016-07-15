<?php

namespace Demo\Controller;

use Settings\Common\CommonActionController;
use Demo\Service\DemoService;
use Zend\View\Model\JsonModel;

class IndexController extends CommonActionController
{
    public function indexAction()
    {
        /**
         * @var DemoService $titanusService
         */
        $titanusService = $this->container->get('Demo\Service\TestService');

        return new JsonModel([
            'name' => 'Aram Baghdasaryan',
            'multiplier' => [
                '5 * 6 equal to' => $titanusService->multiplyThings(5, 6),
            ],
        ]);
    }
}

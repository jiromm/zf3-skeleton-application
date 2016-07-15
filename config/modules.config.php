<?php
/**
 * @link      http://github.com/jiromm/zf3-skeleton-application for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Zend\ServiceManager\Di',
    'Zend\Mvc\Console',
    'Zend\Form',
    'Zend\InputFilter',
    'Zend\Filter',
    'Zend\Hydrator',
    'Zend\I18n',
    'Zend\Router',
    'Zend\Db',
    'Zend\Validator',

    'Settings',
    'Demo',
];

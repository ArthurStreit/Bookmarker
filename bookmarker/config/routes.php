<?php
/**
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Bookmarks', 'action' => 'index']);

        $builder->connect('/pages/*', 'Pages::display');

        $builder->fallbacks();
    });

    $routes->scope(
        '/bookmarks',
        ['controller' => 'Bookmarks'],
        function (RouteBuilder $routes) {
            $routes->connect('/tagged/*', ['action' => 'tags']);
        }
    );
};

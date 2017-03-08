<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\ClientBundle\Menu;

use CSBill\CoreBundle\Icon;
use Symfony\Component\HttpFoundation\Request;

class ClientMenu
{
    /**
     * @return array
     */
    public static function main()
    {
        return [
            'client.menu.main',
            [
                'extras' => [
                    'icon' => Icon::CLIENT,
                ],
                'route' => '_clients_index',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function listMenu()
    {
        return [
            'client.menu.list',
            [
                'extras' => [
                    'icon' => Icon::CLIENT,
                ],
                'route' => '_clients_index',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function add()
    {
        return [
            'client.menu.add',
            [
                'extras' => [
                    'icon' => 'user-plus',
                ],
                'route' => '_clients_add',
            ],
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public static function view(Request $request)
    {
        return [
            'client.menu.view',
            [
                'extras' => [
                    'icon' => 'eye',
                ],
                'route' => '_clients_view',
                'routeParameters' => [
                    'id' => $request->get('id'),
                ],
            ],
        ];
    }
}
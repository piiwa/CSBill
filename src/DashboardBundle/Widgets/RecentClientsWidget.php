<?php

declare(strict_types=1);

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\DashboardBundle\Widgets;

use CSBill\ClientBundle\Repository\ClientRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RecentClientsWidget implements WidgetInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->manager = $registry->getManager();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = $this->manager->getRepository('CSBillClientBundle:Client');

        $clients = $clientRepository->getRecentClients();

        return ['clients' => $clients];
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return 'CSBillDashboardBundle:Widget:recent_clients.html.twig';
    }
}

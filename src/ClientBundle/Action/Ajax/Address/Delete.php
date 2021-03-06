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

namespace CSBill\ClientBundle\Action\Ajax\Address;

use CSBill\ClientBundle\Entity\Address;
use CSBill\CoreBundle\Response\AjaxResponse;
use CSBill\CoreBundle\Traits\DoctrineAwareTrait;
use CSBill\CoreBundle\Traits\JsonTrait;

final class Delete implements AjaxResponse
{
    use DoctrineAwareTrait,
        JsonTrait;

    public function __invoke(Address $address)
    {
        $em = $this->doctrine->getManager();

        $em->remove($address);
        $em->flush();

        return $this->json([]);
    }
}

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

namespace CSBill\ClientBundle\Action\Ajax\Contact;

use CSBill\ClientBundle\Entity\Client;
use CSBill\ClientBundle\Entity\Contact;
use CSBill\ClientBundle\Form\Handler\ContactAddFormHandler;
use CSBill\CoreBundle\Response\AjaxResponse;
use SolidWorx\FormHandler\FormHandler;
use Symfony\Component\HttpFoundation\Request;

final class Add implements AjaxResponse
{
    /**
     * @var FormHandler
     */
    private $handler;

    public function __construct(FormHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(Request $request, Client $client)
    {
        $contact = new Contact();
        $contact->setClient($client);

        return $this->handler->handle(ContactAddFormHandler::class, ['contact' => $contact]);
    }
}

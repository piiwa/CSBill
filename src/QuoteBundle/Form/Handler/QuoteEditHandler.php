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

namespace CSBill\QuoteBundle\Form\Handler;

use CSBill\CoreBundle\Response\FlashResponse;
use CSBill\CoreBundle\Templating\Template;
use SolidWorx\FormHandler\FormRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class QuoteEditHandler extends AbstractQuoteHandler
{
    /**
     * {@inheritdoc}
     */
    public function getResponse(FormRequest $formRequest)
    {
        return new Template(
            '@CSBillQuote/Default/edit.html.twig',
            [
                'form' => $formRequest->getForm()->createView(),
                'quote' => $formRequest->getOptions()->get('quote'),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onSuccess($quote, FormRequest $form): ?Response
    {
        /* @var RedirectResponse $response */
        $response = parent::onSuccess($quote, $form);

        return new class($response->getTargetUrl()) extends RedirectResponse implements FlashResponse {
            public function getFlash(): iterable
            {
                yield self::FLASH_SUCCESS => 'quote.action.edit.success';
            }
        };
    }
}

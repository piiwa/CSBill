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

namespace CSBill\ClientBundle\Action\Ajax;

use CSBill\ClientBundle\Entity\Client;
use CSBill\CoreBundle\Response\AjaxResponse;
use CSBill\CoreBundle\Traits\JsonTrait;
use CSBill\MoneyBundle\Formatter\MoneyFormatter;
use Money\Currency;

final class Info implements AjaxResponse
{
    use JsonTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var MoneyFormatter
     */
    private $formatter;

    public function __construct(\Twig_Environment $twig, MoneyFormatter $formatter, Currency $currency)
    {
        $this->twig = $twig;
        $this->currency = $currency;
        $this->formatter = $formatter;
    }

    public function __invoke(Client $client, string $type = 'quote')
    {
        $content = $this->twig->render(
            '@CSBillClient/Ajax/info.html.twig',
            [
                'client' => $client,
                'type' => $type,
            ]
        );

        $currency = $client->getCurrency() ?? $this->currency;

        return $this->json([
            'content' => $content,
            'currency' => $currency->getCode(),
            'currency_format' => $this->formatter->getCurrencySymbol($currency),
        ]);
    }
}

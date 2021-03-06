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

namespace CSBill\PaymentBundle\Action\Ajax;

use CSBill\CoreBundle\Response\AjaxResponse;
use CSBill\PaymentBundle\Entity\PaymentMethod;
use CSBill\PaymentBundle\Factory\PaymentFactories;
use CSBill\PaymentBundle\Form\Handler\PaymentMethodSettingsHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SolidWorx\FormHandler\FormHandler;
use Symfony\Component\HttpFoundation\Request;

final class Settings implements AjaxResponse
{
    /**
     * @var PaymentFactories
     */
    private $factories;

    /**
     * @var FormHandler
     */
    private $handler;

    public function __construct(PaymentFactories $factories, FormHandler $handler)
    {
        $this->factories = $factories;
        $this->handler = $handler;
    }

    /**
     * @ParamConverter("paymentMethod", options={"mapping": {"method": "gatewayName"}})
     */
    public function __invoke(Request $request, ?PaymentMethod $paymentMethod)
    {
        $methodName = $request->attributes->get('method');

        if (null === $paymentMethod) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setGatewayName($methodName);
            $paymentMethod->setFactoryName($this->factories->getFactory($methodName));
            $paymentMethod->setName(ucwords(str_replace('_', ' ', $methodName)));
        }

        return $this->handler->handle(PaymentMethodSettingsHandler::class, ['payment_method' => $paymentMethod]);
    }
}

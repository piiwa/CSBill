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

namespace CSBill\QuoteBundle\Tests\Form\Handler;

use CSBill\CoreBundle\Response\FlashResponse;
use CSBill\CoreBundle\Templating\Template;
use CSBill\FormBundle\Test\FormHandlerTestCase;
use CSBill\InvoiceBundle\Listener\WorkFlowSubscriber as InvoiceWorkFlowSubscriber;
use CSBill\InvoiceBundle\Manager\InvoiceManager;
use CSBill\MoneyBundle\Entity\Money;
use CSBill\QuoteBundle\Entity\Quote;
use CSBill\QuoteBundle\Form\Handler\QuoteEditHandler;
use CSBill\QuoteBundle\Listener\WorkFlowSubscriber;
use CSBill\QuoteBundle\Model\Graph;
use Mockery as M;
use Money\Currency;
use SolidWorx\FormHandler\FormRequest;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\Transition;

class QuoteEditHandlerTest extends FormHandlerTestCase
{
    private $quote;

    protected function setUp()
    {
        parent::setUp();

        $this->quote = new Quote();
        $this->quote->setStatus(Graph::STATUS_DRAFT);
        $this->quote->setDiscount(0.1);

        $this->em->persist($this->quote);
        $this->em->flush();

        Money::setBaseCurrency('USD');
    }

    public function getHandler()
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new InvoiceWorkFlowSubscriber($this->registry));
        $invoiceStateMachine = new StateMachine(
            new Definition(
                ['new', 'draft'],
                [new Transition('new', 'new', 'draft')]
            ),
            new SingleStateMarkingStore('status'),
            $dispatcher,
            'invoice'
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new WorkFlowSubscriber($this->registry, M::mock(InvoiceManager::class), $invoiceStateMachine));
        $stateMachine = new StateMachine(
            new Definition(
                ['draft', 'pending'],
                [new Transition('send', 'draft', 'pending')]
            ),
            new SingleStateMarkingStore('status'),
            $dispatcher,
            'quote'
        );

        $router = M::mock(RouterInterface::class);
        $router->shouldReceive('generate')
            ->zeroOrMoreTimes()
            ->with('_quotes_view', ['id' => 1])
            ->andReturn('/quotes/1');

        $handler = new QuoteEditHandler($router, $stateMachine);
        $handler->setDoctrine($this->registry);

        Money::setBaseCurrency('USD');

        return $handler;
    }

    protected function assertOnSuccess(?Response $response, $quote, FormRequest $form)
    {
        /* @var Quote $quote */

        $this->assertSame(Graph::STATUS_PENDING, $quote->getStatus());
        $this->assertSame(0.2, $quote->getDiscount());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertInstanceOf(FlashResponse::class, $response);
        $this->assertCount(1, $response->getFlash());
        $this->assertCount(1, $this->em->getRepository('CSBillQuoteBundle:Quote')->findAll());
    }

    protected function assertResponse(FormRequest $formRequest)
    {
        $this->assertInstanceOf(Template::class, $formRequest->getResponse());
    }

    public function getFormData(): array
    {
        return [
            'quote' => [
                'discount' => 20,
            ],
            'save' => 'pending',
        ];
    }

    protected function getHandlerOptions(): array
    {
        return [
            'quote' => $this->quote,
            'form_options' => [
                'currency' => new Currency('USD'),
            ],
        ];
    }

    protected function getEntityNamespaces(): array
    {
        return [
            'CSBillClientBundle' => 'CSBill\ClientBundle\Entity',
            'CSBillQuoteBundle' => 'CSBill\QuoteBundle\Entity',
            'CSBillPaymentBundle' => 'CSBill\PaymentBundle\Entity',
            'CSBillTaxBundle' => 'CSBill\TaxBundle\Entity',
        ];
    }

    protected function getEntities(): array
    {
        return [
            'CSBillClientBundle:Client',
            'CSBillQuoteBundle:Quote',
            'CSBillTaxBundle:Tax',
        ];
    }
}

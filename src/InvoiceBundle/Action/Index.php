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

namespace CSBill\InvoiceBundle\Action;

use CSBill\CoreBundle\Templating\Template;
use CSBill\InvoiceBundle\Model\Graph;
use CSBill\InvoiceBundle\Repository\InvoiceRepository;
use CSBill\PaymentBundle\Repository\PaymentRepository;
use Symfony\Component\HttpFoundation\Request;

final class Index
{
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    public function __construct(InvoiceRepository $invoiceRepository, PaymentRepository $paymentRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(Request $request)
    {
        return new Template(
            '@CSBillInvoice/Default/index.html.twig',
            [
                'status_list_count' => [
                    Graph::STATUS_PENDING => $this->invoiceRepository->getCountByStatus(Graph::STATUS_PENDING),
                    Graph::STATUS_PAID => $this->invoiceRepository->getCountByStatus(Graph::STATUS_PAID),
                    Graph::STATUS_CANCELLED => $this->invoiceRepository->getCountByStatus(Graph::STATUS_CANCELLED),
                    Graph::STATUS_DRAFT => $this->invoiceRepository->getCountByStatus(Graph::STATUS_DRAFT),
                    Graph::STATUS_OVERDUE => $this->invoiceRepository->getCountByStatus(Graph::STATUS_OVERDUE),
                ],
                'total_income' => $this->paymentRepository->getTotalIncome(),
                'total_outstanding' => $this->invoiceRepository->getTotalOutstanding(),
            ]
        );
    }
}

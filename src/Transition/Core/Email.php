<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Core;

use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Traits\ServiceContainer;

/**
 * @mixin \OxidEsales\Eshop\Core\Email
 */
class Email extends Email_parent
{
    use ServiceContainer;

    protected $MIMEBody = '';
    protected $MIMEHeader = '';

    protected $attachInvoice = null;

    public function sendOrderEmailToUser($order, $subject = null)
    {
        $invoiceDataService = $this->getServiceFromContainer(Invoice::class);
        $generator = $this->getServiceFromContainer(InvoiceGeneratorInterface::class);

        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($order->getId());
        $generator->generate($invoiceData);

        $this->attachInvoice = $invoiceData->getInvoicePath();

        return $this->faCallParentSendOrderEmailToUser($order, $subject);
    }

    public function send()
    {
        if ($this->attachInvoice) {
            $this->addAttachment(
                path: $this->attachInvoice,
                name: 'example.pdf',
            );
            $this->attachInvoice = null;
        }

        return $this->faCallParentSend();
    }

    public function faCallParentSend()
    {
        return parent::send();
    }

    public function faCallParentSendOrderEmailToUser($order, mixed $subject)
    {
        return parent::sendOrderEmailToUser($order, $subject);
    }

    protected function clearMailer()
    {
        parent::clearMailer();

        $this->MIMEBody = '';
        $this->MIMEHeader = '';
    }
}

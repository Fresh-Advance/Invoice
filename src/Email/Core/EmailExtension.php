<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Email\Core;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Email\Service\InvoiceFilenameCalculatorInterface;
use FreshAdvance\Invoice\Email\Settings\EmailSettingsInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\Eshop\Application\Model\Order;

/**
 * @mixin \OxidEsales\Eshop\Core\Email
 */
class EmailExtension extends EmailExtension_parent
{
    use ServiceContainer;

    protected $MIMEBody = '';
    protected $MIMEHeader = '';

    protected ?string $attachInvoicePath = null;
    protected ?string $attachInvoiceFilename = null;

    public function sendOrderEmailToUser($order, $subject = null)
    {
        $emailSettings = $this->getServiceFromContainer(EmailSettingsInterface::class);
        if ($emailSettings->isSendInvoiceOnUserOrderEmailActive()) {
            $this->setAttachmentFilePathAndNameByFormat(
                $order,
                $emailSettings->getUserOrderEmailInvoiceFilenameFormat()
            );
        }

        return $this->faCallParentSendOrderEmailToUser($order, $subject);
    }

    public function sendOrderEmailToOwner($order, $subject = null)
    {
        $emailSettings = $this->getServiceFromContainer(EmailSettingsInterface::class);
        if ($emailSettings->isSendInvoiceOnOwnerOrderEmailActive()) {
            $this->setAttachmentFilePathAndNameByFormat(
                $order,
                $emailSettings->getOwnerOrderEmailInvoiceFilenameFormat()
            );
        }

        return $this->faCallParentSendOrderEmailToOwner($order, $subject);
    }

    public function send()
    {
        if ($this->attachInvoicePath && $this->attachInvoiceFilename) {
            $this->addAttachment(
                path: $this->attachInvoicePath,
                name: $this->attachInvoiceFilename,
            );
            $this->attachInvoiceFilename = null;
        }

        return $this->faCallParentSend();
    }

    /**
     * @codeCoverageIgnore not testable because of parent call
     *
     * @return bool
     */
    public function faCallParentSend()
    {
        return parent::send();
    }

    /**
     * @codeCoverageIgnore not testable because of parent call
     *
     * @param Order $order
     * @param ?string $subject
     *
     * @return bool
     */
    public function faCallParentSendOrderEmailToUser($order, $subject = null)
    {
        return parent::sendOrderEmailToUser($order, $subject);
    }

    /**
     * @codeCoverageIgnore not testable because of parent call
     *
     * @param Order $order
     * @param ?string $subject
     *
     * @return bool
     */
    public function faCallParentSendOrderEmailToOwner($order, $subject = null)
    {
        return parent::sendOrderEmailToOwner($order, $subject);
    }

    private function setInvoiceFilePath(InvoiceDataInterface $invoiceData): void
    {
        if (!$this->attachInvoicePath) {
            $generator = $this->getServiceFromContainer(InvoiceGeneratorInterface::class);
            $generator->generate($invoiceData);

            $this->attachInvoicePath = $invoiceData->getInvoicePath();
        }
    }

    private function getOrderInvoiceData(Order $order): InvoiceDataInterface
    {
        $invoiceDataService = $this->getServiceFromContainer(Invoice::class);
        return $invoiceDataService->getInvoiceDataByOrderId($order->getId());
    }

    private function setAttachmentFilePathAndNameByFormat(Order $order, string $format): void
    {
        $invoiceData = $this->getOrderInvoiceData($order);
        $this->setInvoiceFilePath($invoiceData);

        $invoiceFileNameCalculator = $this->getServiceFromContainer(InvoiceFilenameCalculatorInterface::class);
        $this->attachInvoiceFilename = $invoiceFileNameCalculator->calculateByFormat($format, $invoiceData);
    }

    /**
     * @return void
     */
    protected function clearMailer()
    {
        parent::clearMailer();

        $this->MIMEBody = '';
        $this->MIMEHeader = '';
    }
}

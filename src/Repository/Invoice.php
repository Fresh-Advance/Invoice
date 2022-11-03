<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Repository;

use Doctrine\DBAL\ForwardCompatibility\Result;
use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class Invoice
{
    public function __construct(
        protected QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
    }

    public function getOrderInvoice(string $orderId): ?InvoiceConfigurationInterface
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
            ->from('fa_invoices')
            ->where('order_id = :order_id')
            ->setParameter('order_id', $orderId);

        /** @var Result $result */
        $result = $queryBuilder->execute();
        if ($data = $result->fetchAssociative()) {
            return new InvoiceConfiguration(
                orderId: is_string($data['order_id']) ? $data['order_id'] : '',
                signer: is_string($data['invoice_signer']) ? $data['invoice_signer'] : '',
                date: is_string($data['invoice_date']) ? $data['invoice_date'] : '',
                number: is_string($data['invoice_number']) ? $data['invoice_number'] : '',
            );
        }

        return null;
    }

    public function saveInvoiceConfiguration(InvoiceConfigurationInterface $invoiceConfiguration): void
    {
        if ($this->getOrderInvoice($invoiceConfiguration->getOrderId())) {
            $this->updateOrderInvoice($invoiceConfiguration);
        } else {
            $this->createOrderInvoice($invoiceConfiguration);
        }
    }

    protected function createOrderInvoice(InvoiceConfigurationInterface $invoiceConfiguration): void
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        $queryBuilder->insert('fa_invoices')
            ->values($this->getValuesForUpdate())
            ->setParameters($this->mapParameters($invoiceConfiguration));

        $queryBuilder->execute();
    }

    protected function updateOrderInvoice(InvoiceConfigurationInterface $invoiceConfiguration): void
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        $queryBuilder->update('fa_invoices');
        foreach ($this->getValuesForUpdate() as $key => $value) {
            $queryBuilder->set($key, $value);
        }
        $queryBuilder->setParameters($this->mapParameters($invoiceConfiguration))
            ->where('order_id = :order_id');

        $queryBuilder->execute();
    }

    private function getValuesForUpdate(): array
    {
        return [
            'order_id' => ':order_id',
            'invoice_number' => ':invoice_number',
            'invoice_signer' => ':invoice_signer',
            'invoice_date' => ':invoice_date'
        ];
    }

    private function mapParameters(InvoiceConfigurationInterface $invoiceConfiguration): array
    {
        return [
            ':order_id' => $invoiceConfiguration->getOrderId(),
            ':invoice_number' => $invoiceConfiguration->getNumber(),
            ':invoice_signer' => $invoiceConfiguration->getSigner(),
            ':invoice_date' => $invoiceConfiguration->getDate(),
        ];
    }
}

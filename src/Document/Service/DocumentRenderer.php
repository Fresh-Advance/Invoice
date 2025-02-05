<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Language\Service\LanguageInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;

class DocumentRenderer implements DocumentRendererInterface
{
    public const INVOICE_TEMPLATE = '@fa_invoice/invoice/body';

    public function __construct(
        private readonly LanguageInterface $shopLanguage,
        private readonly TemplateRendererInterface $templateRenderer,
        private readonly TemplateParametersServiceInterface $templateParametersService,
    ) {
    }

    public function render(InvoiceDataInterface $invoiceData): string
    {
        $currentLanguage = $this->shopLanguage->getTplLanguage();
        try {
            $this->shopLanguage->forceSetTplLanguage((int)$invoiceData->getLanguageId());
            $html = $this->templateRenderer->renderTemplate(
                self::INVOICE_TEMPLATE,
                $this->templateParametersService->calculateTemplateParameters($invoiceData)
            );
        } finally {
            $this->shopLanguage->forceSetTplLanguage((int)$currentLanguage);
        }

        return $html;
    }
}

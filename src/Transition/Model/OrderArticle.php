<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Model;

use OxidEsales\Eshop\Application\Model\Article;

/**
 * @mixin \OxidEsales\Eshop\Application\Model\OrderArticle
 */
class OrderArticle extends OrderArticle_parent
{
    public function faGetTranslatedTitle(int $languageId): string
    {
        /** @var Article $article */
        $article = oxNew(Article::class);
        $article->loadInLang($languageId, $this->getProductId());
        $title = $article->getFieldData('oxtitle');

        if (!$title && $this->getParentId()) {
            /** @var Article $parentProduct */
            $parentProduct = oxNew(Article::class);
            $parentProduct->loadInLang($languageId, $this->getParentId());
            $title = $parentProduct->getFieldData('oxtitle');
        }

        /** @var string|null $title */
        return (string)$title;
    }
}

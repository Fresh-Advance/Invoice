<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

class_alias(
    \OxidEsales\Eshop\Core\Email::class,
    \FreshAdvance\Invoice\Email\Core\EmailExtension_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Model\OrderArticle::class,
    \FreshAdvance\Invoice\Pdf\Model\OrderArticleExtension_parent::class
);

class_alias(
    \OxidEsales\Eshop\Core\Language::class,
    \FreshAdvance\Invoice\Language\Extension\Language_parent::class
);

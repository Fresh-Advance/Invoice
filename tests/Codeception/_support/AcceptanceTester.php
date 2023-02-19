<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Codeception;

use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Admin\AdminLoginPage;
use OxidEsales\Codeception\Admin\AdminPanel;
use OxidEsales\Codeception\Page\Home;
use FreshAdvance\Invoice\Tests\Codeception\_generated\AcceptanceTesterActions;

final class AcceptanceTester extends \Codeception\Actor
{
    use AcceptanceTesterActions;

    /**
     * Open shop first page.
     */
    public function openShop(): Home
    {
        $I        = $this;
        $homePage = new Home($I);
        $I->amOnPage($homePage->URL);

        return $homePage;
    }

    public function loginAdmin(): AdminPanel
    {
        $I = $this;

        $adminLoginPage = new AdminLoginPage($I);
        $I->amOnPage($adminLoginPage->URL);

        $admin = Fixtures::get('admin');
        return $adminLoginPage->login($admin['email'], $admin['password']);
    }
}

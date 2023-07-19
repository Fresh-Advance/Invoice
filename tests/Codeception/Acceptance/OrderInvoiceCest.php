<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Codeception\Acceptance;

use DateTime;
use FreshAdvance\Invoice\Tests\Codeception\AcceptanceTester;
use FreshAdvance\Invoice\Tests\Codeception\Page\InvoicePage;
use OxidEsales\Codeception\Module\Translation\Translator;

/**
 * @group fa_invoice
 */
final class OrderInvoiceCest
{
    private $orderId = 'justSomeOxorderId';
    private $orderArticleId = 'justSomeOxorderArticleID';
    private $articleId = 'justSomeOxArticleID';

    /** @param AcceptanceTester $I */
    public function _before(AcceptanceTester $I)
    {
        $this->insertAnOrderInDatabase($I);
    }

    public function testOrderInvoiceTabAvailable(AcceptanceTester $I): void
    {
        $I->wantToTest('Order Invoice tab is available');

        $adminPanel = $I->loginAdmin();

        $orders = $adminPanel->openOrders();
        $orders->find($orders->orderNumberInput, '2');

        $I->selectListFrame();
        $I->click(Translator::translate('tbclorder_fa_invoice'));
        $I->selectEditFrame();

        $invoicePage = new InvoicePage($I);

        $dateExample = 'some date';
        $numberExample = '25';
        $signerExample = 'some signer';

        $I->fillField($invoicePage->invoiceDateField, $dateExample);
        $I->fillField($invoicePage->invoiceNumberField, $numberExample);
        $I->fillField($invoicePage->invoiceSignerField, $signerExample);

        $I->click($invoicePage->invoiceDataSaveButton);
        $I->waitForPageLoad();

        $I->seeInField($invoicePage->invoiceDateField, $dateExample);
        $I->seeInField($invoicePage->invoiceNumberField, $numberExample);
        $I->seeInField($invoicePage->invoiceSignerField, $signerExample);
    }

    /** @param AcceptanceTester $I */
    private function insertAnOrderInDatabase(AcceptanceTester $I): void
    {
        $I->haveInDatabase(
            'oxorder',
            [
                'OXID' => $this->orderId,
                'OXSHOPID' => 1,
                'OXUSERID' => 'someUserID',
                'OXORDERDATE' => (new DateTime())->format('Y-m-d 00:00:00'),
                'OXPAID' => (new DateTime())->format('Y-m-d 00:00:00'),
                'OXORDERNR' => 2,
                'OXBILLDATE' => (new DateTime())->format('Y-m-d'),
                'OXSENDDATE' => (new DateTime())->format('Y-m-d'),
                'OXBILLEMAIL' => 'example01@oxid-esales.dev',
                'OXBILLFNAME' => 'name',
                'OXBILLLNAME' => 'surname',
                'OXBILLSTREET' => 'street',
                'OXBILLSTREETNR' => '1',
                'OXBILLCITY' => 'city',
                'OXBILLCOUNTRYID' => 'a7c40f631fc920687.20179984',
                'OXBILLSTATEID' => 'BB',
                'OXBILLZIP' => '3000',
                'OXPAYMENTID' => 'NotRegisteredPaymentId',
                'OXPAYMENTTYPE' => 'oxidcashondel',
                'OXREMARK' => 'remark text',
                'OXTRANSSTATUS' => 'OK',
                'OXFOLDER' => 'ORDERFOLDER_NEW',
                'OXDELTYPE' => 'oxidstandard',
                'OXTIMESTAMP' => (new DateTime())->format('Y-m-d 00:00:00'),
                'OXCARDTEXT' => ''
            ]
        );

        $I->haveInDatabase(
            'oxorderarticles',
            [
                'OXID' => $this->orderArticleId,
                'OXORDERID' => $this->orderId,
                'OXAMOUNT' => 1,
                'OXARTID' => $this->articleId,
                'OXARTNUM' => '1002-1',
                'OXTITLE' => 'Test product 2 [EN] šÄßüл',
                'OXSHORTDESC' => 'Test product 2 short desc [EN] šÄßüл',
                'OXSELVARIANT' => 'var1 [EN] šÄßüл',
                'OXNETPRICE' => 46.22,
                'OXBRUTPRICE' => 55,
                'OXVATPRICE' => 8.78,
                'OXVAT' => 19,
                'OXSTOCK' => 5,
                'OXINSERT' => '2008-02-04',
                'OXTIMESTAMP' => (new DateTime())->format('Y-m-d 00:00:00'),
                'OXSEARCHKEYS' => 'šÄßüл1002',
                'OXISSEARCH' => 1,
                'OXORDERSHOPID' => 1,
                'OXDELIVERY' => (new DateTime())->format('Y-m-d'),
                'OXPERSPARAM' => '',
            ]
        );

        $I->haveInDatabase(
            'oxarticles',
            [
                'OXID' => $this->articleId,
                'OXTITLE' => 'Test product 2 [EN] šÄßüл',
                'OXSHORTDESC' => 'Test product 2 short desc [EN] šÄßüл',
                'OXACTIVEFROM' => (new DateTime())->format('Y-m-d 00:00:00'),
                'OXACTIVETO' => (new DateTime())->modify('+1 week')->format('Y-m-d 00:00:00'),
                'OXDELIVERY' => (new DateTime())->format('Y-m-d 00:00:00'),
                'OXINSERT' => (new DateTime())->format('Y-m-d 00:00:00'),
            ]
        );
    }
}

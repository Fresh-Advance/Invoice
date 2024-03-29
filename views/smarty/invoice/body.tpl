[{assign var="order" value=$invoice->getOrder() }]
[{assign var="shop" value=$invoice->getShop() }]
[{assign var="configuration" value=$invoice->getInvoiceConfiguration() }]

<style>
    #buyer, #seller {
        width: 50%;
        float: left;
    }

    .contactHead {
        font-weight: bold;
        margin-bottom: 10px;
    }

    #number, #date, #invoiceSign {
        text-align: right;
        font-weight: bold;
        margin: 25px 0;
    }

    #facture {
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        margin: 25px 0;
    }

    #contentTable {
        width: 100%;
        border-width: 0;
        margin: 15px 0;
    }

    td {
        font-size: 12px;
        padding: 5px 0;
        border-top: solid 1px black;
    }

    .contentHeader td {
        font-weight: bold;
    }

    td.itemCount,
    td.itemPrice,
    td.itemTotalPrice {
        text-align: right;
    }

    .itemSize,
    .itemCode,
    .itemCount  {
        width: 10%;
    }

    .itemPrice,
    .itemTotalPrice {
        width: 15%;
    }

    #delivery td, #total td, #discount td, #vouchers td, #payment td, .totalvats td {
        text-align: right;
    }
</style>

<div id="seller">
    <div class="contactHead">[{oxmultilang ident="FA_INVOICE_SELLER"}]</div>
    <div>
        <strong>[{$shop->getFieldData('OXCOMPANY')}]</strong><br/>
        [{$shop->getFieldData('OXSTREET')}]<br/>
        [{$shop->getFieldData('OXZIP')}] [{$shop->getFieldData('OXCITY')}], [{$shop->getFieldData('OXCOUNTRY')}]<br/>
        [{oxmultilang ident="FA_INVOICE_TAXID"}]: [{$shop->getFieldData('OXTAXNUMBER')}]<br/>
        [{$shop->getFieldData('OXINFOEMAIL')}]
    </div>
</div>

<div id="buyer">
    <div class="contactHead">[{oxmultilang ident="FA_INVOICE_BUYER"}]</div>
    <div>
        [{if $order->getFieldData('oxbillcompany') }]
            <strong>[{$order->getFieldData('oxbillcompany')}]</strong><br/>
        [{/if}]

        [{if $order->getFieldData('oxbillustid') }]
            [{$order->getFieldData('oxbillustid')}]<br/>
        [{/if}]

        [{$order->getFieldData('OXBILLFNAME')}] [{$order->getFieldData('OXBILLLNAME')}]<br/>
        [{$order->getFieldData('OXBILLZIP')}] [{$order->getFieldData('OXBILLCITY')}], [{$order->getBillCountry()}]<br/>
        [{$order->getFieldData('OXBILLEMAIL')}]
    </div>
</div>

<div></div>

<div id="number">[{oxmultilang ident="FA_INVOICE_ORDERNR"}]: [{$order->getFieldData('oxordernr')}]</div>
<div id="date">[{oxmultilang ident="FA_INVOICE_DATE"}]: [{$configuration->getDate()}]</div>

<div id="facture">[{oxmultilang ident="FA_INVOICE_NUMBER"}] [{$configuration->getNumber()}]</div>

<table id="contentTable">
    <tr class="contentHeader">
        <td class="itemTitle">[{oxmultilang ident="FA_INVOICE_ITEM_TITLE"}]</td>
        <td class="itemCode">[{oxmultilang ident="FA_INVOICE_ITEM_CODE"}]</td>
        <td class="itemSize">[{oxmultilang ident="FA_INVOICE_ITEM_TYPE"}]</td>
        <td class="itemCount">[{oxmultilang ident="FA_INVOICE_ITEM_COUNT"}]</td>
        <td class="itemPrice">[{oxmultilang ident="FA_INVOICE_ITEM_PRICE"}]</td>
        <td class="itemTotalPrice">[{oxmultilang ident="FA_INVOICE_ITEM_PRICE_TOTAL"}]</td>
    </tr>

    [{foreach from=$order->getOrderArticles() item=item}]
        <tr class="item">
            <td class="itemTitle">[{$item->faGetTranslatedTitle($invoice->getLanguageId())}]</td>
            <td class="itemCode">[{$item->getFieldData('oxartnum')}]</td>
            <td class="itemSize">[{oxmultilang ident="FA_INVOICE_PCS"}]</td>
            <td class="itemCount">[{$item->getFieldData('oxamount')}]</td>
            <td class="itemPrice">[{$item->getNetPriceFormated()}] [{$order->getFieldData('oxcurrency')}]</td>
            <td class="itemTotalPrice">[{$item->getTotalNetPriceFormated()}] [{$order->getFieldData('oxcurrency')}]</td>
        </tr>
    [{/foreach}]

    [{if $order->getFieldData('oxdiscount') }]
        <tr id="discount">
            <td colspan="5">[{oxmultilang ident="FA_INVOICE_DISCOUNT"}]:</td>
            <td>- [{$order->getFormattedDiscount()}] [{$order->getFieldData('oxcurrency')}]</td>
        </tr>
    [{/if}]

    [{if $order->getFieldData('oxvoucherdiscount') }]
        <tr id="vouchers">
            <td colspan="5">[{oxmultilang ident="FA_INVOICE_VOUCHERS"}]:</td>
            <td>- [{$order->getFormattedTotalVouchers()}] [{$order->getFieldData('oxcurrency')}]</td>
        </tr>
    [{/if}]

    [{if $order->getFieldData('oxdelcost') }]
        <tr id="delivery">
            <td colspan="5">[{oxmultilang ident="FA_INVOICE_DELIVERY"}]:</td>
            <td>[{$order->getFormattedDeliveryCost()}] [{$order->getFieldData('oxcurrency')}]</td>
        </tr>
    [{/if}]

    [{if $order->getFieldData('oxpaycost') }]
        <tr id="payment">
            <td colspan="5">[{oxmultilang ident="FA_INVOICE_PAYMENT"}]:</td>
            <td>[{$order->getFormattedPayCost()}] [{$order->getFieldData('oxcurrency')}]</td>
        </tr>
    [{/if}]

    [{foreach key=vat from=$order->getProductVats() item=vatPrice}]
        <tr class="totalvats">
            <td colspan="5">[{oxmultilang ident="FA_INVOICE_VATS"}] ([{$vat}]%):</td>
            <td>[{$vatPrice}] [{$order->getFieldData('oxcurrency')}]</td>
        </tr>
    [{/foreach}]

    <tr id="total">
        <td colspan="5">[{oxmultilang ident="FA_INVOICE_TOTAL"}]:</td>
        <td>[{$order->getFormattedTotalOrderSum()}] [{$order->getFieldData('oxcurrency')}]</td>
    </tr>

</table>

[{math assign="sumWithCents" equation="a*b" a=$order->getFieldData('oxtotalordersum') b=100}]
[{oxmultilang ident="FA_INVOICE_TOTAL_IN_WORDS"}]: [{$wording->currencyToWords($sumWithCents, $order->getFieldData('oxcurrency'))}]

<div id="invoiceSign">[{oxmultilang ident="FA_INVOICE_SIGNED"}]: [{$configuration->getSigner()}]</div>

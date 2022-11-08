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
    .itemCount,
    .itemPrice,
    .itemTotalPrice {
        width: 10%;
    }

    #total td {
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
            <strong>[{$order->getFieldData('oxbillcompany')}]<br/>
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

        [{* Load product to get title in correct language *}]
        [{assign var="product" value=$item->getArticle()}]
        [{$product->loadInLang($invoice->getLanguageId(), $product->getId())}]

        <tr class="item">
            <td class="itemTitle">[{$product->getFieldData('oxtitle')}]</td>
            <td class="itemCode">[{$item->getFieldData('oxartnum')}]</td>
            <td class="itemSize">[{oxmultilang ident="FA_INVOICE_PCS"}]</td>
            <td class="itemCount">[{$item->getFieldData('oxamount')}]</td>
            <td class="itemPrice">[{$item->getNetPriceFormated()}]</td>
            <td class="itemTotalPrice">[{$item->getTotalNetPriceFormated()}]</td>
        </tr>
    [{/foreach}]

    <tr id="total">
        <td colspan="5">[{oxmultilang ident="FA_INVOICE_TOTAL"}]:</td>
        <td>[{$order->getFormattedTotalOrderSum()}]</td>
    </tr>

</table>

<div id="invoiceSign">[{oxmultilang ident="FA_INVOICE_SIGNED"}]: [{$configuration->getSigner()}]</div>
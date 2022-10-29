[{assign var="order" value=$invoice->getOrder() }]

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
    [{assign var="shop" value=$invoice->getShop() }]
    <div class="contactHead">PARDAVĖJAS</div>
    <div>
        <strong>[{$shop->getFieldData('OXCOMPANY')}]</strong><br/>
        [{$shop->getFieldData('OXSTREET')}]<br/>
        [{$shop->getFieldData('OXZIP')}] [{$shop->getFieldData('OXCITY')}], [{$shop->getFieldData('OXCOUNTRY')}]<br/>
        Įmonės kodas: [{$shop->getFieldData('OXTAXNUMBER')}]<br/>
        [{$shop->getFieldData('OXINFOEMAIL')}]
    </div>
</div>

<div id="buyer">
    <div class="contactHead">PIRKĖJAS</div>
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

<div id="number">Užsakymo Nr.: [{$order->getFieldData('oxordernr')}]</div>
<div id="date">Sąskaitos išrašymo data: ????</div>

<div id="facture">Sąskaita faktūra Nr. ?? – ????</div>

<table id="contentTable">
    <tr class="contentHeader">
        <td class="itemTitle">PAVADINIMAS</td>
        <td class="itemCode">KODAS</td>
        <td class="itemSize">MAT. VNT</td>
        <td class="itemCount">KIEKIS</td>
        <td class="itemPrice">KAINA</td>
        <td class="itemTotalPrice">VISO</td>
    </tr>

    [{foreach from=$order->getOrderArticles() item=item}]

        [{* Load product to get title in correct language *}]
        [{assign var="product" value=$item->getArticle()}]
        [{$product->loadInLang($invoice->getLanguageId(), $product->getId())}]

        <tr class="item">
            <td class="itemTitle">[{$product->getFieldData('oxtitle')}]</td>
            <td class="itemCode">[{$item->getFieldData('oxartnum')}]</td>
            <td class="itemSize">vnt.</td>
            <td class="itemCount">[{$item->getFieldData('oxamount')}]</td>
            <td class="itemPrice">[{$item->getNetPriceFormated()}]</td>
            <td class="itemTotalPrice">[{$item->getTotalNetPriceFormated()}]</td>
        </tr>
    [{/foreach}]

    <tr id="total">
        <td colspan="5">Total:</td>
        <td>[{$order->getFormattedTotalOrderSum()}]</td>
    </tr>

</table>

<div id="invoiceSign">Sąskaitą išrašė: ???</div>
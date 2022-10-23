[{assign var="order" value=$invoice->getOrder() }]
[{assign var="shop" value=$invoice->getShop() }]

<style>
    #buyer, #seller {
        width: 50%;
        float: left;
    }

    .contactHead {
        font-weight: bold;
    }

    #number, #date {
        text-align: right;
        font-weight: bold;
        margin: 10px 0;
    }

    #facture {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
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
    <div class="contactHead">PARDAVĖJAS</div>
</div>
<div id="buyer">
    <div class="contactHead">PIRKĖJAS</div>
    <div></div>
</div>
<div></div>

<div id="number">Užsakymo Nr.: [{$order->getFieldData('oxordernr')}]</div>
<div id="date">Sąskaitos išrašymo data: 2022-10-17</div>

<div id="facture">Sąskaita faktūra Nr. GA – 074</div>

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
        [{assign var="product" value=$item->getArticle()}]
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
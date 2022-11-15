[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice">
</form>

[{assign var="configuration" value=$invoiceData->getInvoiceConfiguration() }]

<form action="[{$oViewConf->getSelfLink()}]" method="POST">
    [{$oViewConf->getHiddenSid()}]

    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice">
    <input type="hidden" name="fnc" value="generate">

    <input type="hidden" name="invoice[order_id]" value="[{$oxid}]">
    <div>Sąskaitos išrašymo data: <input type="text" name="invoice[date]" value="[{$configuration->getDate()}]"></div>
    <div>Sąskaitos Nr.: <input type="text" name="invoice[number]" value="[{$configuration->getNumber()}]"></div>
    <div>Sąskaitą išrašė: <input type="text" name="invoice[signer]" value="[{$configuration->getSigner()}]"></div>
    <div><input type="submit" value="Generate"></div>
</form>

<br/>
<form action="[{$oViewConf->getSelfLink()}]" method="POST" target="_BLANK">
    [{$oViewConf->getHiddenSid()}]

    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice">
    <input type="hidden" name="fnc" value="downloadOrderInvoice">

    <input type="hidden" name="orderId" value="[{$oxid}]">
    <div><input type="submit" value="Show invoice document"></div>
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
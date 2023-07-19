[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice_admin">
</form>

[{assign var="configuration" value=$invoiceData->getInvoiceConfiguration() }]

<form action="[{$oViewConf->getSelfLink()}]" method="POST">
    [{$oViewConf->getHiddenSid()}]

    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice_admin">
    <input type="hidden" name="fnc" value="saveData">

    <input type="hidden" name="invoice[order_id]" value="[{$oxid}]">
    <div>[{oxmultilang ident="FA_INVOICE_FORM_DATE"}]: <input type="text" name="invoice[date]" value="[{$configuration->getDate()}]"></div>
    <div>[{oxmultilang ident="FA_INVOICE_FORM_NUMBER"}]: <input type="text" name="invoice[number]" value="[{$configuration->getNumber()}]"></div>
    <div>[{oxmultilang ident="FA_INVOICE_FORM_SIGNER"}]: <input type="text" name="invoice[signer]" value="[{$configuration->getSigner()}]"></div>
    <div><input type="submit" name="saveData" value="[{oxmultilang ident="FA_INVOICE_FORM_SAVE_DATA"}]"></div>
</form>

<br/>
<form action="[{$oViewConf->getSelfLink()}]" method="POST" target="_BLANK">
    [{$oViewConf->getHiddenSid()}]

    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice">
    <input type="hidden" name="fnc" value="downloadOrderInvoice">

    <input type="hidden" name="orderId" value="[{$oxid}]">
    <div><input type="submit" name="download" value="[{oxmultilang ident="FA_INVOICE_FORM_DOWNLOAD"}]"></div>
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]

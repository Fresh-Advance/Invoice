[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice">
</form>

[{if $order}]
    <form action="[{$oViewConf->getSelfLink()}]" method="POST">
        [{$oViewConf->getHiddenSid()}]

        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="cl" value="fa_invoice">
        <input type="hidden" name="fnc" value="generate">

        <input type="hidden" name="invoice[order_id]" value="[{$order->getId()}]">
        <div>Sąskaitos išrašymo data: <input type="text" name="invoice[date]"></div>
        <div>Sąskaitos Nr.: <input type="text" name="invoice[number]"></div>
        <div>Sąskaitą išrašė: <input type="text" name="invoice[signer]"></div>
        <div><input type="submit" value="Generate"></div>
    </form>
[{/if}]

<br/>
<form action="[{$oViewConf->getSelfLink()}]" method="POST" target="_BLANK">
    [{$oViewConf->getHiddenSid()}]

    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fa_invoice">
    <input type="hidden" name="fnc" value="downloadOrderInvoice">

    <input type="hidden" name="orderId" value="[{$order->getId()}]">
    <div><input type="submit" value="Show invoice document"></div>
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
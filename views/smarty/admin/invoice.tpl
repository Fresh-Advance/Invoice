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

        <input type="hidden" name="orderId" value="[{$order->getId()}]">
        <div>Sąskaitos išrašymo data: <input type="text" name="date"></div>
        <div>Sąskaitos Nr.: <input type="text" name="invoiceNr"></div>
        <div>Sąskaitą išrašė: <input type="text" name="signer"></div>
        <div><input type="submit" value="Generate"></div>
    </form>
[{/if}]

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
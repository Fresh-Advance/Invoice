[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="oscpaypalorder">
</form>

[{if $order}]
    <a href="[{$oViewConf->getShopUrl()}]?cl=fa_invoice_generate&orderId=[{$order->getId()}]&fnc=generate"
       target="_BLANK">Get invoice</a>
[{/if}]

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
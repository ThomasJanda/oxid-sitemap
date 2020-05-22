[{include file="headitem.tpl" title="" box=" "}]
[{oxscript include="js/libs/jquery.min.js"}]

<h1>Sitemap erstellen</h1>

[{if $status!=""}]
    <div>[{$status}]</div>
[{/if}]

<form action="[{ $oViewConf->getSelfLink() }]" enctype="multipart/form-data" method="post" id="form_generate">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="action" value="generate">
    <input type="hidden" name="typ" value="[{$typ}]">
    <input type="hidden" name="offset" value="[{$offset}]">
</form>

[{if $reload==false}]
    <button type="button" id="start_generate" onclick="submit_form_generate(); ">Erstellen</button>

    [{assign var=url value=$oView->getfileurl()}]
    [{if $url!=""}]
        <div>
            <form method="post" action="[{$url}]" target="_blank">
                <input type="hidden" name="uid" value="[{php}] echo uniqid();  [{/php}]">
                <button type="submit">Vorhandene Sitemap</button>
            </form>
        </div>
    [{/if}]
[{/if}]

[{capture}]<script>[{/capture}]
[{capture name="rsscript"}]

    function submit_form_generate()
    {
        $('#form_generate').submit();
    }
    [{if $reload==true}]
        submit_form_generate();
    [{/if}]

[{/capture}]
[{capture}]</script>[{/capture}]
[{oxscript add=$smarty.capture.rsscript}]


[{include file="bottomitem.tpl"}]
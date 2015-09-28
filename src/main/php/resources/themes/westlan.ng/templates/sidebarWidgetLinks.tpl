<div class = "box">
    <h2>{$linksCollection->getTitle()}</h2>
    
    <dl>
    {foreach from = $linksCollection item = "link"}
        <dt><a href = "{$link.url}">{$link.title}</a></dt>
    {/foreach}
    </dl>
</div>

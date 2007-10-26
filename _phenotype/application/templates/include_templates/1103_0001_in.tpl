		<div id="breadcrumb"><br/><br/>
		<a href="index.php?id=1">Startseite</a>
{section name=i loop=$entries}
			<a href="{$entries[i].url}">{$entries[i].title}</a>
{/section}
		</div>
		
<?
// namespace stuff
use \Michelf\Markdown;

$medias = $oo->media($uu->id);

foreach($medias as $m)
{
	$url = m_url($m);
?><div class="mfdiv">
    <a class="fancybox" rel="gallery" href="<? echo $url; ?>">
    	<img class="mf" src="<? echo $url; ?>">
	</a><?
	if($m['caption'])
		echo Markdown::defaultTransform($m['caption']); 
?></div><?
}

$self = $oo->get($uu->id);

if($self['body'])
{
?><div class="mfdiv"><? echo Markdown::defaultTransform($self['body']); ?></div><?
}
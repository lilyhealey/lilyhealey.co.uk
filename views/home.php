<?
define("PORTFOLIO_ID", 1);

$port_obj = $oo->get(PORTFOLIO_ID);
$projects = $oo->children(PORTFOLIO_ID);

foreach($projects as $p)
{
	$url = "/".$port_obj['url']."/".$p['url'];	
?><a class="square" href="<? echo $url; ?>">
	<p class="left"><? echo $p['name1']; ?></p>
</a><?
}
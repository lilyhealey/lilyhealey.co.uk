<?

require_once("views/head.php");

if(!$uu->id)
	require_once("views/home.php");
else
	require_once("views/detail.php");
require_once("views/foot.php");

?>
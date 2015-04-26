<?php

class URL
{
	public $urls;
	public $url;
	public $ids;
	public $id;
	
	function __construct()
	{
		global $ob;
		global $db;
		
		$urls = explode('/', $_SERVER['REQUEST_URI']);
		$urls = array_slice($urls, 3);
		$this->urls = $urls;
		$this->url = $urls[count($urls)-1];
		
		$ids = $ob->urls_to_ids($urls);
		$id = $ids[count($ids)-1];
		if(!$id)
			$id = 0;
		if(sizeof($ids) == 1 && empty($ids[0]))
			unset($ids);
		$this->ids = $ids;
		$this->id = $id;
	}
	
	public function urls()
	{
		global $ob;
		$urls = $ob->ids_to_urls($this->ids);
		$url = implode("/", $urls);
		return $url;
	}
	
	public function back()
	{
		global $ob;
		$ids = $this->ids;
		array_pop($ids);
		$urls = $ob->ids_to_urls($ids);
		$url = implode("/", $urls);
		return $url;
	}
}

?>

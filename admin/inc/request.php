<?php
/*
 * 
 *
 *
 */
class Request
{
	public $page = ''; 	// is this variable even used?
	public $remote_user;// logged in user via .htaccess, .htpasswd
	
	/* object info */
	public $object; 	// comma-separated string
	public $objects; 	// array
	public $o; 			// most recent object id
	public $name;
	public $parents;
	public $children;
		
	/* [add, edit] post variables */
	public $name1;
	public $deck;
	public $body;
	public $notes;
	public $begin;
	public $end;
	public $url;
	public $rank;
	
	/* [add, delete, edit, link] */
	public $submit;
	public $action;
	
	public $m; // media id
	public $medias; // array
	public $types;
	public $captions;
	public $ranks;
	public $deletes;
		
	/* link */
	public $wires_toid;
	
//	public $name;

	function __construct()
	{
		$this->page = basename($_SERVER['PHP_SELF'], ".php");
		$this->remote_user = $_SERVER['REDIRECT_REMOTE_USER'];
		
		// post variables
		$vars = array(	'object', 
						'action', 'submit', 
						'name1', 'deck', 'body', 'notes', 'begin', 'end', 'url', 'rank',
						'medias', 'types', 'captions', 'ranks', 'deletes',
						'wires_toid');

		foreach($vars as $v)	
			$this->$v = $_REQUEST[$v];
		
		// handle multiple objects
		$objects = explode(",", $this->object);
		
		// $o refers to most recent object
		$o = $objects[count($objects)-1];
		if(!$o)
			$o = 0;
		if (sizeof($objects) == 1 && empty($objects[0])) 
			unset($objects);
		$this->objects = $objects;
		$this->o = $o;
		
		// variables to set
		// $name
		// $o
		// $object
		// $objects
		// $parents
		// $children
	}
	
	public function url_data()
	{
		$url = "?object=";
		if($this->objects)
			$url .= implode(",", $this->objects);
		return $url;
	}
	
	public function url_back()
	{
		$objects = $this->objects;
		array_pop($objects);
		$url = "?object=" . implode(",", $objects);
		return $url;
	}
	
	// public function parents()
// 	{
// 		$parents[] = ""; // is this necessary?
// 		$objects = $this->objects;
// 		$ob = new Objects();
// 
// 		for ($i = 0; $i < count($objects) - 1; $i++) 
// 		{
// 			$item = $ob->get($objects[$i]);
// 			$name = strip_tags($item["name1"]);
// 
// 			// Each panel expands on title click
// 			$parents[$i]["url"] = $admin_path . "browse.php?object=";
// 			for ($j = 0; $j < $i + 1; $j++)
// 			{
// 				$parents[$i]["url"] .= $objects[$j];
// 				if ($j < $i)
// 					$parents[$i]["url"] .= ",";
// 			}
// 			$parents[$i]["name"] = $name;
// 		}
// 		
// 		if($parents[0] == "")
// 			unset($parents);
// 		return $parents;
// 	}
}

?>
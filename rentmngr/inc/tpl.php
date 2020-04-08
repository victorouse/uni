<?php
	
	include "inc/rain.tpl.class.php";
	raintpl::configure("base_url", "" );
	raintpl::configure("tpl_dir", "tpl/" );
	raintpl::configure("cache_dir", "tmp/" );
	raintpl::configure( 'path_replace', true );
	raintpl::configure( 'path_replace_list', array('img') );

?>
<?php
	$name = md5("text").".txt";

	$fp = fopen($name, "w+");
	@fwrite($fp, "Contact Me");
	fclose($fp);
	echo "Complete";
	//echo file_get_contents(md5("text").".txt");
	
?>

<?php
	$name = md5("url").".txt";

	$fp = fopen($name, "w+");
	@fwrite($fp, "Contact Me");
	@fclose($fp);
	sleep(1);
	if(file_exists($name)){
		echo "Existe bro";
	}else{
		echo "Fuck you bro";
	}
	
?>

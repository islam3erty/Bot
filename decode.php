<?php
	$name = md5("text").".txt";

	/*$fp = fopen($name, "w+");
	@fwrite($fp, "Contact Me");
	@fclose($fp);
	echo "Complete";*/
	if(file_exists($name)){
		echo "Existe bro";
	}else{
		echo "Fuck you bro";
	}
	
?>

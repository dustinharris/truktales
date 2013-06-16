<?php
//because the $_POST array is an associative array
foreach($_POST as $key=>$value){
	//assign a temporary variable to value and kill the whitespace
	$temp;
	if(is_array($value)){
		$temp=$value;
	}
	else{
		$temp=trim($value);
	}
	
	//if the temp is EMPTY, but REQUIRED, add it to the $missing array
	if(empty($temp) && in_array($key, $required)){
		$missing[] = $key;
	} elseif(in_array($key, $expected)){
		//otherwise, make the variable the name of the key!
		${$key}=$temp;
	}

}
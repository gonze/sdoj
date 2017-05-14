<?php

require_once __DIR__ . '/../config/config.php';

if(!$con){
	$con= mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);

	if(!$con){
   		 echo 'Can not connect to mysql!';
    		throw new Exception('Can not connect to mysql: ' . mysqli_connect_error());
	}

	mysqli_select_db($con, DB_NAME);
	mysqli_set_charset($con, DB_CHARSET);
}


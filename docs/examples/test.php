<?php

include_once("anti-xsrf.php");

$pdo = new PDO("mysql:host=127.0.0.1;dbname=testdb;charset=utf8", "root", "password");

$anti_xsrf = new anti_xsrf($pdo);

echo "Anti-XSRF Token: " . $anti_xsrf->generate_token();

if (isset($_GET["test_token"])) {
	if ($anti_xsrf->token_valid($_GET["test_token"])) {
		echo "Valid Token";
	}
}	

?>

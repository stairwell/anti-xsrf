<?php

class anti_xsrf {

	private $conn;
	private $table_name = "anti_xsrf";
	private $length = 32;
	private $timeout = 300;

	public function __construct(PDO $pdo, $table_name = "anti_xsrf", $length = 32, $timeout = 300) {
		$this->conn = $pdo;

		if (is_string($table_name)) {	
			$this->table_name = $table_name;
		}

		if (is_int($length)) {
			$this->length = $length;
		}

		if (is_int($timeout)) {
			$this->timeout = $timeout;
		}

		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	public function generate_token() {
		if (($token_string = $this->store_token())) {
			return $token_string;
		} else {
			return "null";
		}
	}


	public function token_valid($token_string) {
		try {
			$statement = $this->conn->prepare("SELECT * FROM $this->table_name WHERE token_string = ? AND token_used = 0");
			$statement->execute(array(
				$token_string
			));
			$result = $statement->fetch();

			echo "<hr>" .time() . " - " . $result["token_expires"];
	
			if (time() < $result["token_expires"]) {
				$this->mark_token_used($result["token_id"]);
				return true;
			} else {
				return false;
			} 
		} catch(PDOException $e) {
			return false;
		}
	}

	private function store_token() {
		try {
			$timeout = time() + $this->timeout;
			$token_string = str_shuffle(bin2hex(openssl_random_pseudo_bytes($this->length)));
			$statement = $this->conn->prepare("REPLACE INTO $this->table_name (token_string, token_expires) VALUES(?, ?)");
			$statement->execute(array(
				$token_string,
				$timeout
			));
			return $token_string;
		} catch(PDOException $e) {
			return false;
		}
	}

	private function mark_token_used($id) {
		$statement = $this->conn->prepare("UPDATE $this->table_name SET token_used = 1 WHERE token_id = ?");
		$statement->execute(array(
			$id
		));
	}
};

?>

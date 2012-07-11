<?php
class User {
	/* local variables */
	var $loginID;
	var $name;
	var $email;
	var $username;
	
	/* constructor */
	function User($loginID, $name, $email, $username) {
		$this->loginID = $loginID;
		$this->name = $name;
		$this->email = $email;
		$this->username = $username;
	}
}
?>
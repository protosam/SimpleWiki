<?php
class users extends catalyst {
	public $table_name = 'users';
	public $primary_key = 'user_id';

	public function valid_username($username){
		if(strlen($username) < 3 || strlen($username) > 25)
			return "Username length must be 3 to 25 characters long.";

		if(!preg_match('/^[A-Za-z0-9_-]+$/i', $username))
			return "Username may only contain A-Z0-9_-";

		return "ok";
	}

	public function valid_email($email){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			return "Email does not appear valid.";

		return "ok";
	}


	public function hash_pass($password, $new_salt=false){
		if($new_salt){
			$this->set("password_salt", md5(md5(uniqid(rand(), true))) );
		}

		$salt = $this->get("password_salt");
		$pass = "";

		for($i=0;$i<strlen($salt) || $i<strlen($password);$i++){
			if(isset($password[$i]))
				$pass .= $password[$i];
			if(isset($salt[$i]))
				$pass .= $salt[$i];
		}

		return md5($pass);
	}

	public function mktoken(){
		return md5(md5(uniqid(rand(), true)));
	}


	public function login($email, $password, $remember_me = false){
		$this->findby('email', $email);
		$hpass = $this->hash_pass($password);

		if($this->count() == 0){
			return false;
		}

		if($this->get('password_hash') == $hpass){
			$this->set("login_token", $this->mktoken());
			$this->save();

			if($remember_me){
				setcookie("uid", $this->get("user_id"), time()+2592000); // 30 day cookie
				setcookie("token", $this->get("login_token"), time()+2592000); // 30 day cookie
			}else{
				setcookie("uid", $this->get("user_id"));
				setcookie("token", $this->get("login_token"));
			}
			return true;
		}

		return false; // on success
	}

	public function logout(){
		if($this->logged_in()){
			$this->set("login_token", $this->mktoken());
			$this->save();

			setcookie("uid", '');
			setcookie("token", '');
		}
	}

	public function logged_in(){
		if(!isset($_COOKIE['uid']) || !isset($_COOKIE['token']))
			return false;

		$this->findby("user_id", $_COOKIE['uid']);
		if($this->get('login_token') == $_COOKIE['token']){
			return true;
		}

		return false; // or false
	}
}

<?php

class UserHandling
{
	static public function GetUsersDatabaseConnection()
	{
		$mysqli = new mysqli("localhost", ADMIN, PASSWORD, DATABASE);
		if (!$mysqli)
		{ echo 'Failed to connect to database'; }
		return $mysqli;
	}
	
	static public function getUserWorksWithStatus($user_id, $status)
	{
		$mysqli = UserHandling::GetUsersDatabaseConnection();
		$result = $mysqli->query("SELECT work_id FROM works_read_per_user WHERE user_id = $user_id and status = '$status'");
		$booksRead = [];
		
		if ($result)
		{
			while ($row = mysqli_fetch_assoc($result))
			{
				$booksRead[] = $row['work_id'];
			}
		}
		return $booksRead;
	}
	
	static public function getBooksReadByUser($user_id)
	{
		return UserHandling::getUserWorksWithStatus($user_id, 'read');
	}
	
	static public function setBookStatus($user_id, $work_id, $status)
	{
		$mysqli = UserHandling::GetUsersDatabaseConnection();
		$query = "INSERT INTO works_read_per_user (user_id, work_id, status) VALUES ($user_id, $work_id, '$status') ON DUPLICATE KEY UPDATE status = VALUES(status);";
		$result = $mysqli->query($query);
		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	static public function GetUserPresets($user_id)
	{
		$mysqli = UserHandling::GetUsersDatabaseConnection();
		$result = $mysqli->query("SELECT * FROM user_presets WHERE user_id='$user_id'");
		return $result;
	}
	
	static public function AddUserPreset($user_id,$user_name,$preset_name,$preset_url)
	{
	    $mysqli = UserHandling::GetUsersDatabaseConnection();
	    $query = "INSERT INTO user_presets (user_id,user_name,preset_name,preset_url) VALUES ('$user_id','$user_name','$preset_name','$preset_url')";
	    $mysqli->query($query);
	}
	
	static public function DeleteUserPreset($preset_id)
	{
	    $mysqli = UserHandling::GetUsersDatabaseConnection();
	    $mysqli->query("DELETE FROM user_presets WHERE preset_id='$preset_id'");
	}
	
	static public function getUserLevel($user_name)
	{
		// Nonusers are at level 1 by default.
		if ($user_name == 'none') {return 1;}
		
		$mysqli = UserHandling::GetUsersDatabaseConnection();
		$results = $mysqli->query("SELECT access_level FROM users WHERE user_name='$user_name'");
		return mysqli_fetch_assoc($results)['access_level'];
	}
	
	static public function register()
	{
		$mysqli = UserHandling::GetUsersDatabaseConnection();
		
		$err = array();
		
		if (isset($_POST['user_submit']) && $_POST['user_submit']=='Register')
		{
			// If the Register form has been submitted
			
			if(strlen($_POST['username'])<4 || strlen($_POST['username'])>32)
			{
				$err[]='Your username must be between 3 and 32 characters! Please try again.';
			}
			
			if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
			{
				$err[]='Your username contains invalid characters! Please try again.';
			}
			
			if (!isset($_SERVER['REMOTE_ADDR'])) { $ip_address = "none"; }
			else { $ip_address = $_SERVER['REMOTE_ADDR']; }
			
			if(!count($err))
			{
				// If there are no errors
				
				$pass = mysqli_real_escape_string($mysqli,$_POST['password']);
				// Assign password.
				
				$_POST['email'] = mysqli_real_escape_string($mysqli,$_POST['email']);
				$_POST['username'] = mysqli_real_escape_string($mysqli,$_POST['username']);
				// Escape the input data
				
				$query = "INSERT INTO users(user_name,password,email,ip_address,active_since)
								VALUES(
									'".$_POST['username']."',
									'".md5($pass)."',
									'".$_POST['email']."',
									'".$ip_address."',
									NOW()
								);";
				
				mysqli_query($mysqli,$query);
				
				
				if(mysqli_affected_rows($mysqli)==1)
				{
					// send_mail(	'nathaniel_conroy@alumni.brown.edu',
								// $_POST['email'],
								// 'Your Metacanon Password',
								// 'Your password is: '.$pass);

					$_SESSION['msg']['reg-success']='You\'re account is now set up. We\'ve sent you an email with your password for your records!';
				}
				else $err[]=$mysqli->error;
			}
		}
		else
		{
			$err[]='Form failed to submit.';
		}
		
		if(count($err))
		{
			$_SESSION['msg']['reg-err'] = implode('<br />',$err);
		}	
	}
	
	static public function logOff()
	{
		$_SESSION = array();
		session_destroy();
	}

	static public function logIn($set_cookies=true)
	{	
		$mysqli = UserHandling::GetUsersDatabaseConnection();
		
		// Those two files can be included only if INCLUDE_CHECK is defined

		session_name('tzLogin');
		// Starting the session

		session_set_cookie_params(2*7*24*60*60);
		// Making the cookie live for 2 weeks

		if (!isset($_SESSION))
		{
			session_start();
		}


		if(isset($_POST['user_submit']) && $_POST['user_submit']=='Login')
		{
			// Checking whether the Login form has been submitted
			
			$err = array();
			// Will hold our errors
			
			if(!$_POST['username'] || !$_POST['password'])
				$err[] = 'All the fields must be filled in!';
			
			if(!count($err))
			{
				$_POST['username'] = mysqli_real_escape_string($mysqli, $_POST['username']);
				$_POST['password'] = mysqli_real_escape_string($mysqli, $_POST['password']);
				$_POST['rememberMe'] = (int)$_POST['rememberMe'];
				
				// Escaping all input data
				
				$results = mysqli_query($mysqli,"SELECT user_id,user_name FROM users WHERE user_name='{$_POST['username']}' OR email='{$_POST['username']}' AND password='".md5($_POST['password'])."'");
				
				$row = mysqli_fetch_assoc($results);
				
				if($row['user_name'])
				{
					// If everything is OK UserHandling
					
					$_SESSION['usr']=$row['user_name'];
					$_SESSION['user_id'] = $row['user_id'];
					$_SESSION['rememberMe'] = $_POST['rememberMe'];
					
					// Store some data in the session
					
					if ($set_cookies) {
						setcookie('tzRemember',$_POST['rememberMe']);
					}
				}
				else $err[]='Wrong username and/or password!';
			}
			
			
			if($err)
			{
				$_SESSION['msg']['UserHandling-err'] = implode('<br />',$err);
			}
			else
			{
				$_SESSION['msg']['UserHandling-err'] = "";
			}
			// Save the error messages in the session.
			
			//exit;
		}
	}
}

$script = '';

?>

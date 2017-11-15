<?php

class UserHandling
{   
	public function getBooksReadByUser($user)
	{
		$mysqli = GetUsersDatabaseConnection();
		$result = $mysqli->query("SELECT booksread FROM metausers WHERE user = '$user'");
		return $result;
	}
	
	public function MarkBookAsRead($user,$book_id)
	{
	    $mysqli = GetUsersDatabaseConnection();
	    $query = "UPDATE metausers SET booksread = CONCAT(booksread, $book_id) WHERE user = $user";
	    echo $query;
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
	
	public function MarkBookAsUnread($user,$book_id)
	{
	    $mysqli = GetUsersDatabaseConnection();
	    $result = $mysqli->query("UPDATE metausers SET booksread = REPLACE(booksread, $book_id, ',') WHERE user = $user");
	    if ($result)
	    {
	        return true;
	    }
	    else
	    {
	        return false;
	    }
	}
	
	public function GetUserPresets($user_id)
	{
		$mysqli = GetUsersDatabaseConnection();
		$result = $mysqli->query("SELECT * FROM metacanonuserpresets WHERE userid='$user_id'");
		return $result;
	}
	
	public function AddUserPreset($user_id,$user_name,$preset_name,$preset_url)
	{
	    $mysqli = GetUsersDatabaseConnection();
	    $query = "INSERT INTO metacanonuserpresets (userid,username,presetname,preseturl) VALUES ('$user_id','$user_name','$preset_name','$preset_url')";
	    $result = $mysqli->query($query);
	}
	
	public function DeleteUserPreset($preset_id)
	{
	    $mysqli = GetUsersDatabaseConnection();
	    $result = $mysqli->query("DELETE FROM metacanonuserpresets WHERE presetid='$preset_id'");
	}
	
	public function register()
	{
		$mysqli = GetUsersDatabaseConnection();
		
		if (isset($_POST['user_submit']) && $_POST['user_submit']=='Register')
		{
			// If the Register form has been submitted
			
			$err = array();
			
			if(strlen($_POST['username'])<4 || strlen($_POST['username'])>32)
			{
				$err[]='Your username must be between 3 and 32 characters! Please try again.';
			}
			
			if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
			{
				$err[]='Your username contains invalid characters! Please try again.';
			}
			
			if(!checkEmail($_POST['email']))
			{
				$err[]='Your email is not valid! Please try again.';
			}
			
			if(!count($err))
			{
				// If there are no errors
				
				$pass = mysqli_real_escape_string($mysqli,$_POST['password']);
				// Assign password.
				
				$_POST['email'] = mysqli_real_escape_string($mysqli,$_POST['email']);
				$_POST['username'] = mysqli_real_escape_string($mysqli,$_POST['username']);
				// Escape the input data
				
				$query = "	INSERT INTO metausers(user,pass,email,regIP,dt,booksread)
								VALUES(
								
									'".$_POST['username']."',
									'".md5($pass)."',
									'".$_POST['email']."',
									'".$_SERVER['REMOTE_ADDR']."',
									" . $_SERVER['REQUEST_TIME'] . ",
									','
									
								)";
				
				mysqli_query($mysqli,$query);
				
				if(mysqli_affected_rows($mysqli)==1)
				{
					send_mail(	'nathaniel_conroy@alumni.brown.edu',
								$_POST['email'],
								'Your Metacanon Password',
								'Your password is: '.$pass);

					$_SESSION['msg']['reg-success']='You\'re account is now set up. We\'ve sent you an email with your password for your records!';
				}
				else $err[]='This username is already taken!';
			}

			if(count($err))
			{
				$_SESSION['msg']['reg-err'] = implode('<br />',$err);
			}	
		}
	}
	
	public function logOff()
	{
		$_SESSION = array();
		session_destroy();
	}

	public function logIn()
	{	
		$mysqli = GetUsersDatabaseConnection();
		
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
				
				$row = mysqli_fetch_assoc(mysqli_query($mysqli,"SELECT id,user FROM metausers WHERE user='{$_POST['username']}' OR email='{$_POST['username']}' AND pass='".md5($_POST['password'])."'"));

				if($row['user'])
				{
					// If everything is OK UserHandling
					
					$_SESSION['usr']=$row['user'];
					$_SESSION['id'] = $row['id'];
					$_SESSION['rememberMe'] = $_POST['rememberMe'];
					
					// Store some data in the session
					
					setcookie('tzRemember',$_POST['rememberMe']);
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

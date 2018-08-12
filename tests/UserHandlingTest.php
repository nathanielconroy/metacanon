<?php

	include '../php/UserHandling.php';
	include('../php/DatabaseConfig.php');
	use PHPUnit\Framework\TestCase;

	class UserHandlingTest extends TestCase
	{
		public function testUserRegistration()
		{
			$testPassed = true;
			
			$_POST['user_submit'] = 'Register';
			$_POST['username'] = 'newUserrrrrrr' . rand(1000,10000);
			$_POST['password'] = 'stupidPassword' . rand(1000,10000);
			$_POST['email'] = 'newuseremail@emailplace.com';
			$_POST['rememberMe'] = true;
			
			UserHandling::register();
			
			if (isset($_SESSION['msg']['reg-err']))
			{
				echo $_SESSION['msg']['reg-err'];
				$testPassed = false;
			}
			
			$this->assertTrue($testPassed);
		}
		
		public function testUserLogin()
		{	
			$_POST['user_submit'] = 'Login';
			$_POST['username'] = "dummyUsr";
			$_POST['password'] = "mypassword";
			$_POST['rememberMe'] = true;
			
			UserHandling::logIn(false);
			
			$this->assertTrue($_SESSION['msg']['UserHandling-err'] == "Wrong username and/or password!");
			
			$_POST['user_submit'] = "Login";
			$_POST['username'] = "testuser";
			$_POST['password'] = "testPassword";
			$_POST['rememberMe'] = true;
			
			UserHandling::logIn(false);
			
			$this->assertTrue($_SESSION['usr'] == "testuser");
		}
	}
?>


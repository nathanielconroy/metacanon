<?php

	include '../php/UserHandling.php';
	include('../php/DatabaseConfig.php');
	use PHPUnit\Framework\TestCase;

	class UserHandlingTest extends TestCase
	{
		public function testUserRegistration()
		{	
			$_POST['user_submit'] = 'Register';
			$_POST['username'] = 'newUserrrrrrr88493848';
			$_POST['password'] = 'stupidPassword';
			$_POST['email'] = 'newuseremail@emailplace.com';
			$_POST['rememberMe'] = true;
			
			UserHandling::register();
			
			$this->assertFalse(isset($_SESSION['msg']['reg-err']));
				
			// Remove the user that we just created.
			$mysqli = UserHandling::GetUsersDatabaseConnection();
			$query = "DELETE FROM metausers WHERE usr = 'newUserrrrrrr88493848'";
			mysqli_query($mysqli,$query);
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
		
		public function testMarkBookAsReadUnread()
		{
			UserHandling::MarkBookAsRead('testuser','1');
			$booksRead = UserHandling::getBooksReadByUser('testuser');
			$this->assertEquals(',1,', $booksRead);
			UserHandling::MarkBookAsUnread('testuser','1');
			$booksRead = UserHandling::getBooksReadByUser('testuser');
			$this->assertEquals(',', $booksRead);
		}
		
		public function testGetUserLevel()
		{
			$this->assertEquals(2, UserHandling::getUserLevel('nathan'));
			$this->assertEquals(1, UserHandling::getUserLevel('testuser'));
		}
	}
?>


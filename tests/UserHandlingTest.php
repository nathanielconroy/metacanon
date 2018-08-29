<?php

	include '../php/UserHandling.php';
	include('../php/DatabaseConfig.php');
	use PHPUnit\Framework\TestCase;

	class UserHandlingTest extends TestCase
	{
		public function testUserRegistration()
		{
			$_SESSION['msg'] = null;
			
			$_POST['user_submit'] = 'Register';
			$_POST['username'] = 'newUserrrrrrr88493848';
			$_POST['password'] = 'stupidPassword';
			$_POST['email'] = 'newuseremail@emailplace.com';
			$_POST['rememberMe'] = true;
			
			UserHandling::register();
			
			if (isset($_SESSION['msg']['reg-err'])) { echo $_SESSION['msg']['reg-err']; }
			$this->assertFalse(isset($_SESSION['msg']['reg-err']));
			
			// Remove the user that we just created.
			$mysqli = UserHandling::GetUsersDatabaseConnection();
			$query = "DELETE FROM users WHERE user_name = 'newUserrrrrrr88493848'";
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
			UserHandling::SetBookStatus(33,1,'read');
			$booksRead = UserHandling::getUserWorksWithStatus(33,'read');
			$this->assertEquals([1], $booksRead);
			UserHandling::SetBookStatus(33,1,'unread');
			$booksRead = UserHandling::getUserWorksWithStatus(33,'read');
			$this->assertEquals([], $booksRead);
		}
		
		public function testGetUserLevel()
		{
			$this->assertEquals(2, UserHandling::getUserLevel('nathan'));
			$this->assertEquals(1, UserHandling::getUserLevel('testuser'));
		}
		
		public function testUserPresets()
		{
			UserHandling::AddUserPreset(33, "testuser", "testpreset1", 
			"index.php?totalbooks=500&numtitles=100&order=score&womenonly=&oneperauth=&yearstart=1900&yearend=1999&gsdata=1&jstordata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&faulkner=yes");
			UserHandling::AddUserPreset(33, "testuser", "testpreset2", 
			"index.php?totalbooks=500&numtitles=100&order=score&womenonly=&oneperauth=&yearstart=1900&yearend=1999&gsdata=1&jstordata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&faulkner=yes");
			
			$results = UserHandling::GetUserPresets(33);
			$num_rows = 0;
			$found_first_preset = false;
			$ids_to_delete = [];
			while ($row = $results->fetch_array())
			{
				if ($row['preset_name'] == "testpreset1") {$found_first_preset = true;}
				$num_rows++;
				$ids_to_delete[] = $row['preset_id'];
			}
			$this->assertTrue($num_rows > 1);
			$this->assertTrue($found_first_preset);
			
			foreach ($ids_to_delete as $id)
			{
				UserHandling::DeleteUserPreset($id);
			}
			
			$num_rows = 0;
			$results = UserHandling::GetUserPresets('testuser');
			while ($row = $results->fetch_array())
			{
				$num_rows++;
			}
			$this->assertTrue($num_rows == 0);
		}
	}
?>


<?php

class TestSuite
{
	private function microtimeFloat()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	public function userRegistrationTest()
	{		
		$testPassed = true;
		
		$_POST['submit'] = 'Register';
		$_POST['username'] = 'newUser' . rand(1000,10000);
		$_POST['password'] = 'stupidPassword' . rand(1000,10000);
		$_POST['email'] = 'newuseremail@emailplace.com';
		$_POST['rememberMe'] = true;
		
		UserHandling::register();
		
		if (isset($_SESSION['msg']['reg-err']))
		{
			echo 'Registration test failed.<br>';
			echo $_SESSION['msg']['reg-err'];
		}
		else
		{
			echo 'Registration test passed.<br>';
		}
	}
	
	public function userLoginTest()
	{
		$testPassed = true;
		
		$_POST['submit'] = 'Login';
		$_POST['username'] = "dummyUsr";
		$_POST['password'] = "mypassword";
		$_POST['rememberMe'] = true;
		
		UserHandling::logIn();
		
		$testPassed = $_SESSION['msg']['login-err'] == "Wrong username and/or password!";
		
		$_POST['submit'] = "Login";
		$_POST['username'] = "testUser";
		$_POST['password'] = "testPassword";
		$_POST['rememberMe'] = true;
		
		UserHandling::logIn();
		
		if (isset($_SESSION['msg']) && $_SESSION['msg']['login-err'] == "Wrong username and/or password!")
		{
			$testPassed = false;
		}
		
		if ($testPassed)
		{
			echo 'User login test passed.';
			echo '<br>The current user is: ' . $_SESSION['usr'] . '.';
		}
		else
		{
			echo $_SESSION['msg']['login-err'];
			echo 'User login test failed.';
			echo '<br>The current user is: ' . $_SESSION['usr'] . '.';
		}
		
		echo '<br>';
	}

	public function onePerAuthorTest()
	{
		$timeStart = TestSuite::microtimeFloat();
		
		$genres = ["novel","novella","collection","other"];
		$queryBuilder = new FictionQueryBuilder("'unitedstates'",1900,1999,"'all'",'ID','newscore',100,0,1,0,1,1,0,1,1,1,$genres,false,"all");
		$myFictionResults = DatabaseAccessor::getOneBookPerAuthor($queryBuilder);

		$authors = array();
		$testSucceeded = true;

		$rowIndex = 0;
		while ( $row = mysqli_fetch_array($myFictionResults) )
		{
			if (in_array($row['fullname'],$authors))
			{
				$authorName = $row['fullname'];
				echo "Unique author test failed on row #$rowIndex. $authorName was found more than once.<br>";
				$testSucceeded = false;
				break;
			}
			else
			{
				$authors[] = ($row['fullname']);
			}
			$rowIndex++;
		}
		
		if ($rowIndex < 10)
		{
			$testSucceeded = false;
		}

		$numberOfResults = $rowIndex + 1;

		$timeEnd = TestSuite::microtimeFloat();
		$time = $timeEnd - $timeStart;

		if ($testSucceeded && $time < 1)
		{
			echo "The one-book-per-author test succeeded in $time seconds with $rowIndex results. You're effing brilliant!<br>";
		}
		else if ($time >= 1)
		{
			echo "The one-book-per-author test returned the correct results, but it took too long ($time seconds). Feel ashamed of yourself.<br>";
		}
		else
		{
			echo "The one-book-per-author test failed! Returned $rowIndex results.<br>";
		}
	}

	public function standardQueryTest()
	{
		$timeStart = TestSuite::microtimeFloat();

		$genres = ["novel","novella","collection","other"];
		$queryBuilder = new FictionQueryBuilder("'unitedstates'",1900,1999,"'all'",'ID','newscore',100,0,1,0,1,1,0,1,1,1,$genres,false,"all");
		$myFictionResults = DatabaseAccessor::getStandardResults($queryBuilder);

		$authors = array();
		$testSucceded = false;

		$rowIndex = 0;
		while ( $row = mysqli_fetch_array($myFictionResults) )
		{
			$rowIndex++;
		}
		
		if ($rowIndex > 50)
		{
			$testSucceeded = true;
		}
		else if ($rowIndex == 0)
		{
			echo "The standard test failed. Query returned 0 results.<br>";
		}
		else if ($rowIndex < 50 && $rowIndex > 0)
		{
			echo "fail<br>";
		}

		$timeEnd = TestSuite::microtimeFloat();
		$time = $timeEnd - $timeStart;

		if ($testSucceeded && $time < 1)
		{
			echo "The standard test succeeded in $time seconds, returning $rowIndex results. You're effing brilliant!<br>";
		}
		else if ($time >= 1)
		{
			echo "The standard	 test returned the correct results, but it took too long ($time seconds). Feel ashamed of yourself.<br>";
		}
		else
		{
			echo "Test failed u bish.";
		}
	}
	
	public function fictionCountTest()
	{
		$timeStart = TestSuite::microtimeFloat();
		
		$genres = ["novel","novella","collection","other"];
		$queryBuilder = new FictionQueryBuilder("'unitedstates'",1900,1999,"'all'",'ID','newscore',100,0,1,0,1,1,0,1,1,1,$genres,false,	"all");
		$count = DatabaseAccessor::getFictionCount($queryBuilder);

		$timeEnd = TestSuite::microtimeFloat();
		$time = $timeEnd - $timeStart;

		if ($count > 0 && $time < 1)
		{
			echo "The fiction count test succeeded in $time seconds. The count is $count. You're effing brilliant!<br>";
		}
		
		else if ($count > 0 && $time >= 1)
		{
			echo "The fiction count test returned the correct results, but it took too long ($time seconds). Feel ashamed of yourself.<br>";
		}
		
		else
		{
			echo "The fiction count test failed";
		}
	}
	
	public function onePerAuthorCountTest()
	{
		$timeStart = TestSuite::microtimeFloat();
		
		$genres = ["novel","novella","collection","other"];
		$queryBuilder = new FictionQueryBuilder("'unitedstates'",1900,1999,"'all'",'ID','newscore',100,0,1,0,1,1,0,1,1,1,$genres,false,	"all");
		$count = DatabaseAccessor::getOnePerAuthFictionCount($queryBuilder);

		$timeEnd = TestSuite::microtimeFloat();
		$time = $timeEnd - $timeStart;

		if ($count > 0 && $time < 1)
		{
			echo "The the one-per-author count test succeeded in $time seconds. The count is $count. You're effing brilliant!<br>";
		}
		
		else if ($count > 0 && $time >= 1)
		{
			echo "The fiction count test returned the correct results, but it took too long ($time seconds). Feel ashamed of yourself.<br>";
		}
		
		else
		{
			echo "The fiction count test failed";
		}
	}
	
	public function onePerAuthorCountWithOffsetTest()
	{
		$timeStart = TestSuite::microtimeFloat();
		
		$genres = ["novel","novella","collection","other"];
		$queryBuilder = new FictionQueryBuilder("'unitedstates'",1900,1999,"'all'",'ID','newscore',100,100,1,0,1,1,0,1,1,1,$genres,false,	"all");
		$count = DatabaseAccessor::getOnePerAuthFictionCount($queryBuilder);

		$timeEnd = TestSuite::microtimeFloat();
		$time = $timeEnd - $timeStart;

		if ($count > 0 && $time < 1)
		{
			echo "The the one-per-author count with offset test succeeded in $time seconds. The count is $count. You're effing brilliant!<br>";
		}
		
		else if ($count > 0 && $time >= 1)
		{
			echo "The fiction count with offset test returned the correct results, but it took too long ($time seconds). Feel ashamed of yourself.<br>";
		}
		
		else
		{
			echo "The one per author with offset count test failed. The count was 0.";
		}
	}
}

?>
<?php

	include('../php/DatabaseAccessor.php');
	include('../php/DatabaseConfig.php');
	include('../php/FictionQueryBuilder.php');
	include('../vendor/autoload.php');
	use PHPUnit\Framework\TestCase;
	use Latitude\QueryBuilder\Conditions;

	class DatabaseAccessorTest extends TestCase
	{
		public function testOnePerAuthor()
		{	
			$genres = ["novel","novella","collection","other"];
			$queryBuilder = new FictionQueryBuilder(["unitedstates"],1900,1999,'all','work_id','score',100,0,1,0,1,1,0,1,1,1,$genres,false,'all','all',null);
			$myFictionResults = DatabaseAccessor::getOneBookPerAuthor($queryBuilder);

			$authors = array();

			while ( $row = mysqli_fetch_array($myFictionResults) )
			{
				// We should never find the same author name twice.
				$this->assertFalse(in_array($row['fullname'],$authors));
			}
		}

		public function testStandardQuery()
		{
			$genres = ["novel","novella","collection","other"];
			$queryBuilder = new FictionQueryBuilder(["unitedstates"],1900,1999,'all','work_id','score',100,0,1,0,1,1,0,1,1,1,$genres,false,'all','all',null);
			$myFictionResults = DatabaseAccessor::getStandardResults($queryBuilder);

			$authors = array();

			$rowIndex = 0;
			while ( $row = mysqli_fetch_array($myFictionResults) )
			{
				$rowIndex++;
			}
			
			// Should be at least 50 results.
			$this->assertTrue($rowIndex > 50);
		}
		
		public function testStandardQueryWithUser()
		{
			$genres = ["novel","novella","collection","other"];
			$queryBuilder = new FictionQueryBuilder(["unitedstates"],1900,1999,'all','work_id','score',100,0,1,0,1,1,0,1,1,1,$genres,false,'all','all',91);
			$myFictionResults = DatabaseAccessor::getStandardResults($queryBuilder);

			$authors = array();

			$rowIndex = 0;
			while ( $row = mysqli_fetch_array($myFictionResults) )
			{
				$rowIndex++;
			}
			
			// Should be at least 50 results.
			$this->assertTrue($rowIndex > 50);
		}
			
		
		public function testFictionCount()
		{
			$genres = ["novel","novella","collection","other"];
			$queryBuilder = new FictionQueryBuilder(["unitedstates"],1900,1999,'all','work_id','score',100,0,1,0,1,1,0,1,1,1,$genres,false,	'all','all',null);
			$count = DatabaseAccessor::getFictionCount($queryBuilder);

			// Count should be at least 50.
			$this->assertTrue($count > 50);
		}
		
		public function testOnePerAuthorCount()
		{
			$genres = ["novel","novella","collection","other"];
			$queryBuilder = new FictionQueryBuilder(["unitedstates"],1900,1999,'all','work_id','score',100,0,1,0,1,1,0,1,1,1,$genres,false,	'all','all',null);
			$count = DatabaseAccessor::getOnePerAuthFictionCount($queryBuilder);

			// Count should be at least 50.
			$this->assertTrue($count > 50);
		}
		
		public function testOnePerAuthorCountWithOffset()
		{
			$genres = ["novel","novella","collection","other"];
			$queryBuilder = new FictionQueryBuilder(["unitedstates"],1900,1999,'all','work_id','score',100,100,1,0,1,1,0,1,1,1,$genres,false,	'all','all',null);
			$count = DatabaseAccessor::getOnePerAuthFictionCount($queryBuilder);

			// Count should be at least 50.
			$this->assertTrue($count > 50);
		}
		
		public function testGetGenres()
		{
			$results = DatabaseAccessor::getGenres();
			$rowIndex = 0;
			while ($row = mysqli_fetch_array($results))
			{
				$rowIndex++;
			}
			
			$this->assertTrue($rowIndex > 5);
		}
		
		public function testGetRegions()
		{
			$results = DatabaseAccessor::getRegions();
			$rowIndex = 0;
			while ($row = mysqli_fetch_array($results))
			{
				$rowIndex++;
			}
			
			$this->assertTrue($rowIndex > 1);
		}
		
		public function testSearchResults()
		{
			$search_term = "toni morrison";
			$results = DatabaseAccessor::getSearchResults($search_term);
			
			$count = 0;
			while ($row = mysqli_fetch_array($results))
			{
				$count++;
			}
			
			$this->assertTrue($count > 1);
			$this->assertEquals($count, DatabaseAccessor::getSearchResultsCount($search_term));
		}
	}
?>


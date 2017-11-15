<?php

class DatabaseAccessor
{
	public function getTotalAuthors($queryBuilder)
	{
		$query = $queryBuilder->getTotalAuthorsQuery();
		$results = DatabaseAccessor::getSQLResults($query);
		$row = mysqli_fetch_array($results);
		return $row['num'];
	}
	
	public function getTopAuthorsByTotalPoints($queryBuilder)
	{
		$query = $queryBuilder->getTopAuthorsByTotalPointsQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getNumberOfBooksByGenre($queryBuilder)
	{
		$query = $queryBuilder->getBooksPerGenreQuery($queryBuilder);
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getNumberOfAuthorsByGender($queryBuilder)
	{
		$query = $queryBuilder->getAuthorsPerGenderQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getNumberOfBooksByGender($queryBuilder)
	{
		$query = $queryBuilder->getBooksPerGenderQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getNumberOfBooksPerAuthor($queryBuilder)
	{
		$query = $queryBuilder->getBooksPerAuthorQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getSearchResults($searchTerm)
	{
		$searchTerm = str_replace("'","''",$searchTerm);
		$searchTerm = htmlspecialchars($searchTerm);
		$query = "SELECT Author_First_Name, Author, fullname, match( fullname )
			AGAINST ( '%{$searchTerm}%' IN BOOLEAN MODE ) AS relevance
			FROM fiction 
			WHERE match( fullname ) AGAINST ( '%{$searchTerm}%' IN BOOLEAN MODE ) 
			GROUP BY fullname, Author_First_Name, Author
			ORDER BY relevance DESC";
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getSearchResultsCount($searchTerm)
	{
		$searchTerm = str_replace("'","''",$searchTerm);
		$searchTerm = htmlspecialchars($searchTerm);
		$query = "SELECT COUNT(DISTINCT fullname) as total
			FROM fiction 
			WHERE match( fullname ) AGAINST ( '%{$searchTerm}%' IN BOOLEAN MODE )";
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getStandardResults($queryBuilder)
	{
		$query = $queryBuilder->getFictionQuery();
		return DatabaseAccessor::getSQLResults($query);
	}

	public function getOneBookPerAuthor($queryBuilder)
	{
		$query = $queryBuilder->getFictionQueryOnePerAuthor();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	public function getFictionCount($queryBuilder)
	{
		$query = $queryBuilder->getFictionCountQuery();
		$fictionCountResult = DatabaseAccessor::getSQLResults($query);
		$row = mysqli_fetch_assoc($fictionCountResult);
		$fictionCount = $row['total'];
		return $fictionCount;
	}
	
	public function getOnePerAuthFictionCount($queryBuilder)
	{
		$query = $queryBuilder->getOnePerAuthCountQuery();
		$fictionCountResult = DatabaseAccessor::getSQLResults($query);
		$row = mysqli_fetch_assoc($fictionCountResult);
		$fictionCount = $row['total'];
		if ($fictionCount < 500)
		{
			return $fictionCount;
		}
		else
		{
			return 500;
		}
	}
	
	public function getBooksForUser()
	{
		$where = "WHERE Year > 1899 AND Year < 2000 AND nation = 'unitedstates'";
		
		$results = DatabaseAccessor::getSQLResults("SELECT ID, newscore, authorgender FROM (

		SELECT *,
					
		(
		((POWER( 2 * googlescholar +1, 1 /4 ) -1)/.7)+ 
		((POWER( 2 * jstorLangLit +1, 1 /4 ) -1) / 1)*(100/(POWER(titlecorpusfreq/125,1/2) +100))+
		((POWER( 2 * alh +1, 1 /4 ) -1) / .4)*(100/(POWER(titlecorpusfreq/125,1/2) +100))+
		((POWER( 2 * americanliterature +1, 1 /4 ) -1) / .6)*(100/(POWER(titlecorpusfreq/125,1/2) +100))+
		nba + pulitzer
		)
		   

		AS newscore FROM fiction $where) AS a ORDER BY newscore DESC

		"
		);
		
		return $results;
	}
	
	private function getDatabaseConnection()
	{
		$mysqli = new mysqli("localhost", ADMIN, PASSWORD, DATABASE);
		if (!$mysqli)
		{ echo 'Failed to connect to database'; }
		return $mysqli;
	}
	
	private function getSQLResults($query)
	{
		$mysqli = DatabaseAccessor::getDatabaseConnection();
		$results = $mysqli->query($query);
		if (!$results) 
		{
			echo("<P>Error performing query: " . $mysqli->error . "</P>");
			exit();
		}
		return $results;
	}
}
?>

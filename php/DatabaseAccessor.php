<?php

class DatabaseAccessor
{
	static public function getTotalAuthors($queryBuilder)
	{
		$query = $queryBuilder->getTotalAuthorsQuery();
		$results = DatabaseAccessor::getSQLResults($query);
		$row = mysqli_fetch_array($results);
		return $row['num'];
	}
	
	static public function getTopAuthorsByTotalPoints($queryBuilder)
	{
		$query = $queryBuilder->getTopAuthorsByTotalPointsQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	static public function getNumberOfBooksByGenre($queryBuilder)
	{
		$query = $queryBuilder->getBooksPerGenreQuery($queryBuilder);
		return DatabaseAccessor::getSQLResults($query);
	}
	
	static public function getNumberOfAuthorsByGender($queryBuilder)
	{
		$query = $queryBuilder->getAuthorsPerGenderQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	static public function getNumberOfBooksByGender($queryBuilder)
	{
		$query = $queryBuilder->getBooksPerGenderQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	static public function getNumberOfBooksPerAuthor($queryBuilder)
	{
		$query = $queryBuilder->getBooksPerAuthorQuery();
		return DatabaseAccessor::getSQLResults($query);
	}
	
	static public function getSearchResults($searchTerm)
	{
		$searchTerm = str_replace("'","''",$searchTerm);
		$searchTerm = htmlspecialchars($searchTerm);
		$query = "SELECT author_first, author_last, fullname, match( fullname )
			AGAINST ( '%{$searchTerm}%' IN BOOLEAN MODE ) AS relevance
			FROM works 
			WHERE match( fullname ) AGAINST ( '%{$searchTerm}%' IN BOOLEAN MODE ) AND genre IN ('novel','collection','novella','other')
			GROUP BY fullname, author_first, author_last
			ORDER BY relevance DESC";
		return DatabaseAccessor::getSQLResults($query);
	}
	
	static public function getSearchResultsCount($searchTerm)
	{
		$searchTerm = str_replace("'","''",$searchTerm);
		$searchTerm = htmlspecialchars($searchTerm);
		$query = "SELECT COUNT(DISTINCT fullname) as total
			FROM works 
			WHERE match( fullname ) AGAINST ( '%{$searchTerm}%' IN BOOLEAN MODE ) AND genre IN ('novel','collection','novella','other')";
		return mysqli_fetch_assoc(DatabaseAccessor::getSQLResults($query))['total'];
	}
	
	static public function getStandardResults($queryBuilder)
	{
		$onePerAuthor = false;
		$query = $queryBuilder->getFictionQuery($onePerAuthor);
		return DatabaseAccessor::getSQLResults($query);
	}

	static public function getOneBookPerAuthor($queryBuilder)
	{
		$onePerAuthor = true;
		$query = $queryBuilder->getFictionQuery($onePerAuthor);
		return DatabaseAccessor::getSQLResults($query);
	}
	
	static public function getFictionCount($queryBuilder)
	{
		$query = $queryBuilder->getFictionCountQuery();
		$fictionCountResult = DatabaseAccessor::getSQLResults($query);
		$row = mysqli_fetch_assoc($fictionCountResult);
		$fictionCount = $row['total'];
		return $fictionCount;
	}
	
	static public function getOnePerAuthFictionCount($queryBuilder)
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
	
	static public function getBooksForUser()
	{
		$where = "WHERE Year > 1899 AND Year < 2000 AND region = 'unitedstates'";
		
		$results = DatabaseAccessor::getSQLResults("SELECT work_id, score, author_gender FROM (

		SELECT *,
					
		(
		((POWER( 2 * google_scholar +1, 1 /4 ) -1)/.7)+ 
		((POWER( 2 * jstor_lang_lit +1, 1 /4 ) -1) / 1)*(100/(POWER(title_corpus_freq/125,1/2) +100))+
		((POWER( 2 * alh +1, 1 /4 ) -1) / .4)*(100/(POWER(title_corpus_freq/125,1/2) +100))+
		((POWER( 2 * american_literature +1, 1 /4 ) -1) / .6)*(100/(POWER(title_corpus_freq/125,1/2) +100))+
		nba + pulitzer
		)
		   

		AS score FROM works $where) AS a ORDER BY score DESC

		"
		);
		
		return $results;
	}
	
	static private function getColumnAsArray($results, $column_name)
	{
		$values = [];
		while ($row = $results->fetch_assoc()) {
			$values[] = $row[$column_name];
		}
		return $values;
	}
	
	static public function getTags()
	{
		return DatabaseAccessor::getSQLResults('SELECT name, human_readable_name, access_level FROM tags;');
	}
	
	static public function getTagsList()
	{
		return DatabaseAccessor::getColumnAsArray(DatabaseAccessor::getSQLResults('SELECT name FROM tags;'),'name');
	}
	
	static public function getGenres()
	{
		return DatabaseAccessor::getSQLResults('SELECT name, human_readable_name, access_level FROM genres;');
	}
	
	static public function getGenresList()
	{
		return DatabaseAccessor::getColumnAsArray(DatabaseAccessor::getSQLResults('SELECT name FROM genres;'),'name');
	}
	
	static public function getRegions()
	{
		return DatabaseAccessor::getSQLResults('SELECT name, human_readable_name, access_level FROM regions;');
	}
	
	static public function getRegionsList()
	{
		return DatabaseAccessor::getColumnAsArray(DatabaseAccessor::getSQLResults('SELECT name FROM regions;'),'name');
	}
	
	static private function getDatabaseConnection()
	{
		$mysqli = new mysqli("localhost", ADMIN, PASSWORD, DATABASE);
		if (!$mysqli)
		{ echo 'Failed to connect to database'; }
		return $mysqli;
	}
	
	static private function getSQLResults($query)
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

<?php

use Latitude\QueryBuilder\Conditions;

class FictionQueryBuilder{

	function __construct(
		$regions,
		$yearStart,
		$yearEnd,
		$gender,
		$groupBy,
		$orderBy,
		$limit,
		$offset,
		$gsWeight,
		$jstorWeight,
		$alhWeight,
		$alWeight,
		$nytWeight,
		$langAndLitWeight,
		$nbaWeight,
		$pulitzerWeight,
		$genres,
		$faulkner,
		$author
	)
	{
		$this->regions = $regions;
		$this->yearStart = $yearStart;
		$this->yearEnd = $yearEnd;
		$this->gender = $gender;
		$this->groupBy = $groupBy;
		
		if ($orderBy == 'newscore')
		{
			$this->orderBy = $orderBy . " DESC";
		}
		else
		{
			$this->orderBy = $orderBy;
		}
		
		$this->limit = $limit;
		$this->offset = $offset;
		$this->gsWeight = $gsWeight;
		$this->jstorWeight = $jstorWeight;
		$this->alhWeight = $alhWeight;
		$this->alWeight = $alWeight;
		$this->nytWeight = $nytWeight;
		$this->langAndLitWeight = $langAndLitWeight;
		$this->nbaWeight = $nbaWeight;
		$this->pulitzerWeight = $pulitzerWeight;
		
		//set corpus frequency correction
		$this->corpusCorrect = "(100/((ifnull(titlecorpusfreq,0)/150)+100))";

		//set non-unique author name correction
		$this->nonUniqueAuthor = "(100/((ifnull(nonuniqueauthor,0)/25)+100))";

		//set nyt corpus frequency correction
		$this->nytCorpusCorrect = "(100/((ifnull(corpusfreqnyt,0)/50)+100))";
		
		// Build points function strings.
		$correctionString = "*($this->corpusCorrect)*($this->nonUniqueAuthor)";
		$genericPointsFunction = "((POWER(2 * ifnull(%s,0) +1, 1/4) -1)/%f)";
		$correctedPointsFunction = $genericPointsFunction = "((POWER(2 * (ifnull(%s,0) $correctionString) +1, 1/4) -1)/%f)";
		
		$this->googleScholarPointsFunction = sprintf($genericPointsFunction,"googlescholar",.7);
		$this->jstorPointsFunction = sprintf($correctedPointsFunction,"jstor",1);
		$this->alhPointsFunction = sprintf($correctedPointsFunction,"alh",.4);
		$this->americanLiteraturePointsFunction = sprintf($correctedPointsFunction,"americanliterature",.6);
		$this->nytPointsFunction = sprintf($correctedPointsFunction,"nyt",.6);
		$this->jstorLangLitPointsFunction = sprintf($correctedPointsFunction,"jstorLangLit",1);
		
		$this->genres = $genres;
		$this->faulkner = $faulkner;
		$this->author = $author;
	}
	
	public function getAuthorsPerGenderQuery()
	{
		$innerSelect = $this->getInnerSelect();
		$query = "SELECT authorgender, COUNT(DISTINCT authorgender, fullname ) AS num 
			FROM ($innerSelect) AS a
			GROUP BY authorgender
			ORDER BY num DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getTotalAuthorsQuery()
	{
		$innerSelect = $this->getInnerSelect();
		$limit = $this->limit;
		$query = "SELECT COUNT(DISTINCT fullname) AS num 
			FROM ($innerSelect) AS a
			ORDER BY num DESC
			LIMIT 0, 25"; 
		return $query;
	}
	
	public function getBooksPerGenderQuery()
	{
		$innerSelect = $this->getInnerSelect();
		$query = "SELECT authorgender, COUNT( authorgender ) AS num
			FROM ($innerSelect) AS a
			GROUP BY authorgender
			ORDER BY num DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getBooksPerAuthorQuery()
	{
		$innerSelect = $this->getInnerSelect();
		$query = "SELECT Author, Author_First_Name, COUNT( fullname ) AS 'num'
			FROM ($innerSelect) AS a
			GROUP BY Author, Author_First_Name, fullname
			ORDER BY `num` DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getBooksPerGenreQuery()
	{
		$innerSelect = $this->getInnerSelect();
		$query = "SELECT genre, COUNT(genre) AS 'num' 
			FROM ($innerSelect) AS a
			GROUP BY genre
			ORDER BY `num` DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getTopAuthorsByTotalPointsQuery()
	{
		$innerSelect = $this->getInnerSelect();
		$query = "SELECT Author, Author_First_Name, fullname, SUM(newscore) AS totalscore
			FROM ($innerSelect) AS a             
			GROUP BY fullname, Author, Author_First_Name 
			ORDER BY totalscore DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getFictionQueryOnePerAuthor()
	{
		$innerCondition = Conditions::make("a.fullname = b.fullname")
		->andWith("a.newscore = b.maxScore")
		->andWith("a.newscore != 0")->sql();
		$innerSelect = $this->getInnerSelect();
		$innerJoin = "SELECT fullname, MAX(newscore) AS maxScore FROM ($innerSelect) as c GROUP BY fullname";
		$select = "SELECT * FROM ($innerSelect) AS a 
			INNER JOIN ($innerJoin) AS b ON $innerCondition 
			ORDER BY ".$this->orderBy." 
			LIMIT ".$this->limit." 
			OFFSET ".$this->offset;
		return $select;
	}
	
	public function getFictionQuery()
	{
		$innerSelect = $this->getInnerSelect();
		
		$select = "SELECT *
		FROM ($innerSelect) as a  
		ORDER BY ".$this->orderBy." 
		LIMIT ".$this->limit." 
		OFFSET ".$this->offset;
		
		return $select;
	}
	
	public function getFictionCountQuery()
	{
		$conditions = $this->getWhereClause();
		$calculatedScore = $this->getCalculatedScoreString();
		$innerSelect = $this->getInnerSelect();
		return "SELECT COUNT('ID') as total FROM ($innerSelect) as a";
	}
	
	public function getOnePerAuthCountQuery()
	{
		$innerCondition = Conditions::make("a.fullname = b.fullname")
		->andWith("a.newscore = b.maxScore")
		->andWith("a.newscore != 0")->sql();
		$innerSelect = $this->getInnerSelect();
		$innerJoin = "SELECT fullname, MAX(newscore) AS maxScore FROM ($innerSelect) as c GROUP BY fullname";
		$select = "SELECT COUNT('fullname') as total FROM ($innerSelect) AS a 
			INNER JOIN ($innerJoin) AS b ON $innerCondition 
			ORDER BY ".$this->orderBy." 
			LIMIT ".$this->limit;
		return $select;
	}
	
	private function getInnerSelect()
	{
		$conditions = $this->getWhereClause();
		$calculatedScore = $this->getCalculatedScoreString(); 
		
		$innerSelect = "SELECT ID, genre, Title, fullname, Author_First_Name, Author, Year, googlescholar, jstor, jstorLangLit,
			americanliterature, alh, pulitzer, nba, nyt, authorgender, ".
			$this->googleScholarPointsFunction." *(".$this->gsWeight.") AS gsscore, ".
			$this->jstorPointsFunction." *($this->jstorWeight) AS jstorscore, ".
			$this->alhPointsFunction." *(".$this->alhWeight.") AS alhscore, ".
			$this->americanLiteraturePointsFunction." *($this->alWeight) AS americanliteraturescore, ".
			$this->nytPointsFunction." *(".$this->nytWeight.") AS nytscore, ".
			$this->jstorLangLitPointsFunction." *(".$this->langAndLitWeight.") AS langlitscore, 
			(100*".$this->corpusCorrect.") AS titlecorpusfreq, 
			(100*".$this->nonUniqueAuthor.") AS nonuniqueauthor, 
			(100*".$this->nytCorpusCorrect.") AS corpusfreqnyt, 
			$calculatedScore AS newscore
		FROM works WHERE $conditions 
		GROUP BY ".$this->groupBy." 
		ORDER BY newscore DESC
		LIMIT 1500"; // TODO : make this limit a variable.
		
		return $innerSelect;
	}
	
	private function getCalculatedScoreString()
	{
		return "
			$this->googleScholarPointsFunction *($this->gsWeight) +
			$this->jstorPointsFunction *($this->jstorWeight) +
			$this->alhPointsFunction *($this->alhWeight) +
			$this->americanLiteraturePointsFunction *($this->alWeight) +
			$this->nytPointsFunction *($this->nytWeight) +
			$this->jstorLangLitPointsFunction *($this->langAndLitWeight) +
			nba * $this->nbaWeight +
			pulitzer * $this->pulitzerWeight
		";
	}
	
	private function getWhereClause()
	{
		$conditions = "Year >= $this->yearStart AND Year <= $this->yearEnd ";
		
		// Just include all regions if we haven't designated any regions or if one of the included regions is 'all.'
		if (!in_array('all',$this->regions) && count($this->regions) > 0)
		{
			$region = $this->regions[0];
			$regionsString = "region = '$region'";
			if (count($this->regions) > 1)
			{
				$region = $this->regions[$i];
				for ($i = 1; $i < count($this->regions); $i++)
				{
					$regionsString .= " OR region = '$region'";
				}
			}
			$conditions .= "AND $regionsString ";
		}
		
		if ($this->gender == "female" OR $this->gender == "male" OR $this->gender == "other")
		{
			$conditions .= "AND authorgender = '$this->gender' ";
		}
		
		if ($this->author != "all")
		{
			$conditions .= "AND fullname = '$this->author' ";
		}
		
		if (!$this->faulkner)
		{
			$conditions .= "AND NOT fullname = 'Faulkner, William' ";
		}
		
		// Add genre conditions.	
		if (count($this->genres) > 0)
		{
			$genresString = "genre LIKE '%" .$this->genres[0]. "%'";
			if (count($this->genres) > 1)
			{
				for ($i = 1; $i < count($this->genres); $i++)
				{
					$genresString .= " OR genre LIKE '%" .$this->genres[$i]. "%'";
				}
			}	
			$conditions .= "AND ($genresString) ";
		}
				
		return $conditions;
	}
}
?>

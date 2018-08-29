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
		$author,
		$tags,
		$user_id
	)
	{
		$this->regions = $regions;
		$this->yearStart = $yearStart;
		$this->yearEnd = $yearEnd;
		$this->gender = $gender;
		$this->groupBy = $groupBy;
		
		if ($orderBy == 'score')
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
		$this->corpusCorrect = "(100/((ifnull(title_corpus_freq,0)/150)+100))";

		//set non-unique author name correction
		$this->nonUniqueAuthor = "(100/((ifnull(nonunique_author,0)/25)+100))";

		//set nyt corpus frequency correction
		$this->nytCorpusCorrect = "(100/((ifnull(corpus_freq_nyt,0)/50)+100))";
		
		// Build points function strings.
		$correctionString = "*($this->corpusCorrect)*($this->nonUniqueAuthor)";
		$genericPointsFunction = "((POWER(2 * ifnull(%s,0) +1, 1/4) -1)/%f)";
		$correctedPointsFunction = $genericPointsFunction = "((POWER(2 * (ifnull(%s,0) $correctionString) +1, 1/4) -1)/%f)";
		
		$this->googleScholarPointsFunction = sprintf($genericPointsFunction,"google_scholar",.7);
		$this->jstorPointsFunction = sprintf($correctedPointsFunction,"jstor",1);
		$this->alhPointsFunction = sprintf($correctedPointsFunction,"alh",.4);
		$this->americanLiteraturePointsFunction = sprintf($correctedPointsFunction,"american_literature",.6);
		$this->nytPointsFunction = sprintf($correctedPointsFunction,"nyt",.6);
		$this->jstorLangLitPointsFunction = sprintf($correctedPointsFunction,"jstor_lang_lit",1);
		
		$this->genres = $genres;
		$this->faulkner = $faulkner;
		$this->author = $author;
		$this->tags = $tags;
		$this->user_id = $user_id;
		
		$this->statisticsLimit = 500;
		$this->defaultLimit = 5000;
	}
	
	public function getAuthorsPerGenderQuery()
	{
		$innerSelect = $this->getInnerSelect($this->statisticsLimit);
		$query = "SELECT author_gender, COUNT(DISTINCT author_gender, fullname ) AS num 
			FROM ($innerSelect) AS a
			GROUP BY author_gender
			ORDER BY num DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getTotalAuthorsQuery()
	{
		$innerSelect = $this->getInnerSelect($this->statisticsLimit);
		$limit = $this->limit;
		$query = "SELECT COUNT(DISTINCT fullname) AS num 
			FROM ($innerSelect) AS a
			ORDER BY num DESC
			LIMIT 0, 25"; 
		return $query;
	}
	
	public function getBooksPerGenderQuery()
	{
		$innerSelect = $this->getInnerSelect($this->statisticsLimit);
		$query = "SELECT author_gender, COUNT( author_gender ) AS num
			FROM ($innerSelect) AS a
			GROUP BY author_gender
			ORDER BY num DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getBooksPerAuthorQuery()
	{
		$innerSelect = $this->getInnerSelect($this->statisticsLimit);
		$query = "SELECT author_last, author_first, COUNT( fullname ) AS 'num'
			FROM ($innerSelect) AS a
			GROUP BY author_last, author_first, fullname
			ORDER BY `num` DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getBooksPerGenreQuery()
	{
		$innerSelect = $this->getInnerSelect($this->statisticsLimit);
		$query = "SELECT genre, COUNT(genre) AS 'num' 
			FROM ($innerSelect) AS a
			GROUP BY genre
			ORDER BY `num` DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getTopAuthorsByTotalPointsQuery()
	{
		$innerSelect = $this->getInnerSelect($this->statisticsLimit);
		$query = "SELECT author_last, author_first, fullname, SUM(score) AS totalscore
			FROM ($innerSelect) AS a             
			GROUP BY fullname, author_last, author_first 
			ORDER BY totalscore DESC
			LIMIT 0,25";
		return $query;
	}
	
	public function getFictionQueryOnePerAuthor()
	{
		$innerCondition = Conditions::make("a.fullname = b.fullname")
		->andWith("a.score = b.maxScore")
		->andWith("a.score != 0")->sql();
		$innerSelect = $this->getInnerSelect($this->defaultLimit);
		$innerJoin = "SELECT fullname, MAX(score) AS maxScore FROM ($innerSelect) as c GROUP BY fullname";
		$select = "SELECT * FROM ($innerSelect) AS a 
			INNER JOIN ($innerJoin) AS b ON $innerCondition 
			ORDER BY ".$this->orderBy." 
			LIMIT ".$this->limit." 
			OFFSET ".$this->offset;
		return $select;
	}
	
	private function getOnePerAuthorJoin()
	{
		$innerCondition = Conditions::make("a.fullname = b.fullname")
		->andWith("a.score = b.maxScore")
		->andWith("a.score != 0")->sql();
		$innerSelect = $this->getInnerSelect($this->defaultLimit);
		$innerJoin = "SELECT fullname, MAX(score) AS maxScore FROM ($innerSelect) as c GROUP BY fullname";
		$join = "INNER JOIN ($innerJoin) AS b ON $innerCondition ";
		return $join;
	}
	
	public function getFictionQuery($onePerAuthor)
	{
		$innerSelect = $this->getInnerSelect($this->defaultLimit);
		
		$select = "SELECT * FROM ($innerSelect) AS a ";
		
		if ($onePerAuthor) { $select .= $this->getOnePerAuthorJoin(); }
		if (isset($this->user_id))
		{
			$user_select = $this->getUserWorksQuery($this->user_id);
			$select .= "LEFT OUTER JOIN ($user_select) AS d ON a.work_id = d.user_work_id ";
		}
		
		$select .= "ORDER BY ".$this->orderBy." 
		LIMIT ".$this->limit." 
		OFFSET ".$this->offset;
		
		return $select;
	}
	
	public function getFictionCountQuery()
	{
		$conditions = $this->getWhereClause();
		$calculatedScore = $this->getCalculatedScoreString();
		$innerSelect = $this->getInnerSelect($this->defaultLimit);
		return "SELECT COUNT(work_id) as total FROM ($innerSelect) as a";
	}
	
	public function getOnePerAuthCountQuery()
	{
		$innerCondition = Conditions::make("a.fullname = b.fullname")
		->andWith("a.score = b.maxScore")
		->andWith("a.score != 0")->sql();
		$innerSelect = $this->getInnerSelect($this->defaultLimit);
		$innerJoin = "SELECT fullname, MAX(score) AS maxScore FROM ($innerSelect) as c GROUP BY fullname";
		$select = "SELECT COUNT('fullname') as total FROM ($innerSelect) AS a 
			INNER JOIN ($innerJoin) AS b ON $innerCondition 
			ORDER BY ".$this->orderBy." 
			LIMIT ".$this->limit;
		return $select;
	}
	
	private function getInnerSelect($limit)
	{
		$conditions = $this->getWhereClause();
		$calculatedScore = $this->getCalculatedScoreString(); 
		
		$innerSelect = "SELECT work_id, genre, title, fullname, author_first, author_last, year, google_scholar, jstor, jstor_lang_lit,
			american_literature, alh, pulitzer, nba, nyt, author_gender, ".
			$this->googleScholarPointsFunction." *(".$this->gsWeight.") AS gs_score, ".
			$this->jstorPointsFunction." *($this->jstorWeight) AS jstor_score, ".
			$this->alhPointsFunction." *(".$this->alhWeight.") AS alh_score, ".
			$this->americanLiteraturePointsFunction." *($this->alWeight) AS american_literature_score, ".
			$this->nytPointsFunction." *(".$this->nytWeight.") AS nyt_score, ".
			$this->jstorLangLitPointsFunction." *(".$this->langAndLitWeight.") AS lang_lit_score, 
			(100*".$this->corpusCorrect.") AS title_corpus_freq, 
			(100*".$this->nonUniqueAuthor.") AS nonunique_author, 
			(100*".$this->nytCorpusCorrect.") AS corpus_freq_nyt, 
			$calculatedScore AS score
		FROM works WHERE $conditions 
		GROUP BY ".$this->groupBy." 
		ORDER BY score DESC
		LIMIT $limit"; // TODO : make this limit a variable.
		
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
			ifnull(nba,0) * $this->nbaWeight +
			ifnull(pulitzer,0) * $this->pulitzerWeight
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
			$conditions .= "AND author_gender = '$this->gender' ";
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
		
		if (count($this->tags) > 0)
		{
			$tagsString = "tags LIKE '%" .$this->tags[0]. "%'";
			if (count($this->tags) > 1)
			{
				for ($i = 1; $i < count($this->tags); $i++)
				{
					$tagsString .= " OR tags LIKE '%" .$this->tags[$i]. "%'";
				}
			}
			$conditions .= "AND ($tagsString) ";
		}
				
		return $conditions;
	}
	
	private function getUserWorksQuery($user_id)
	{
		return "SELECT work_id as user_work_id, status FROM works_read_per_user WHERE user_id = $user_id";
	}
}
?>

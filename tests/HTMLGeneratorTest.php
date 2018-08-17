<?php

	include('../php/HTMLGenerator.php');
	use PHPUnit\Framework\TestCase;

	class HTMLGeneratorTest extends TestCase
	{
		public function testGeneratePresetUrl()
		{	
			$parameters = array(
				'totalbooks' => '500',
				'numtitles' => '100',
				'order' => 'newscore',
				'nonfiction_novel' => 'true',
				'all' => 'true',
				'yearstart' => '1900',
				'yearend' => '1999',
				'gsdata' => '1.00',
				'langlitdata' => '1.00',
				'alhdata' => '1.00',
				'aldata' => '1.00',
				'pdata' => '1.00', 
				'nbadata' => '1.00',
				'nytdata' => '0.00', 
				'jstordata' => '0.00',
				'startnumber' => '0', 
				'order' => 'newscore',
				'faulkner' => '1',
				'usr' => 'testuser',
				'presetname' => 'mypreset',
			);
			$schema = ['totalbooks',
				'numtitles',
				'order',
				'nonfiction_novel',
				'all',
				'yearstart',
				'yearend',
				'gsdata',
				'langlitdata',
				'alhdata',
				'aldata',
				'pdata', 
				'nbadata',
				'nytdata', 
				'jstordata',
				'startnumber', 
				'order',
				'faulkner'];
			$generated_url = HTMLGenerator::generatePresetUrl($parameters, $schema);
			$correct_url = 'index.php?totalbooks=500&numtitles=100&order=newscore&nonfiction_novel=true&all=true&yearstart=1900&yearend=1999&gsdata=1.00&langlitdata=1.00&alhdata=1.00&aldata=1.00&pdata=1.00&nbadata=1.00&nytdata=0.00&jstordata=0.00&startnumber=0&order=newscore&faulkner=1';
			$this->assertEquals($correct_url, $generated_url);
		}
	}
?>


<?php
define('INCLUDE_CHECK',true);

include('../vendor/autoload.php');
include '../UserHandling.php';
require '../connect.php';	
require '../functions.php';
include('../php/DatabaseConfig.php');
include('../php/DatabaseAccessor.php');
include('../php/FictionQueryBuilder.php');
include('TestSuite.php');

use Latitude\QueryBuilder\Conditions;

TestSuite::userRegistrationTest();
TestSuite::userLoginTest();
TestSuite::onePerAuthorTest();
TestSuite::standardQueryTest();
TestSuite::fictionCountTest();
TestSuite::onePerAuthorCountTest();
TestSuite::onePerAuthorCountWithOffsetTest();
?>

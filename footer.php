<div class="w3-container w3-row" style="background: white"></div>
<div class="w3-container w3-row" style="background: #eee;border-top: 1px solid;border-color: #cccccc">

<div class="w3-section w3-col desktoponly" style="text-align:right;padding:10px;padding-top:18px;width:50%"><img src="images/metacanonlogo9medium.png" alt="metacanon logo"></div>
<div class="w3-section w3-col w3-border-left desktoponly" style="text-align:left;padding:10px;width:50%;font-family:Helvetica;font-size:17px;line-height:1.5">
<a href="about.php">about</a><br>
<a href="statistics.php">statistics</a><br>
<a href="userlogin.php"><?php echo isset($_SESSION['user_id']) ? $_SESSION['usr'] : 'login';?></a>
</div>

<div class="w3-container w3-border-bottom mobileonly" style="text-align:center;padding:20px"><img src="images/metacanonlogo9medium.png" alt="metacanon logo"></div>
<div class="w3-container mobileonly" style="padding:10px">
<table width="100px">
<tr>
<td width="38%"></td>
<td width="24%" style="text-align:left;font-family:Helvetica;font-size:17px;line-height:1.5">
<a href="about.php">about</a><br>
<a href="statistics.php">statistics</a><br>
<a href="userlogin.php"><?php echo isset($_SESSION['user_id']) ? $_SESSION['usr'] : 'login';?></a>
</td>
<td width="38%"></td>
</tr>
</table>
</div>



</div>

<footer class="w3-container" style="background: #ddd;text-align:center">
  <p>&copy; Copyright 2015-2018 Nathaniel Conroy</p>
</footer>

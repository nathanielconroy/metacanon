<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1; text/html; charset=UTF-8" http-equiv="Content-Type">
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@metacanonorg" />
<meta name="twitter:title" content="METACANON" />
<meta name="twitter:description" content="A more flexible data-driven alternative to traditional greatest books lists." />
<meta name="twitter:image" content="http://metacanon.org/metacanoncardlogo.jpg" />

<body>

<?php include 'header.php'; ?>

<div class="w3-col l4 s12" style="padding-top:8px;padding-right:8px;padding-left:8px"> 
    <div class="w3-container w3-border w3-card-2">
        <h3>This is not a greatest books list.</h3> 
        <p>Metacanon is a database that offers an alternative to the genre of the greatest books list. Unlike lists compiled by individuals or small committees of experts, metacanon builds custom book lists by culling data from tens of thousands of scholarly texts. This data-driven approach results in a more comprehensive, flexible, and inclusive gateway into the canon(s).</p>
        <p>Metacanon 0.7 is limited primarily to American fiction after 1800, but future updates will expand to include other areas, genres, and periods.</p>
        <p>Use the menu below to generate your own version of the canon.</p>
    </div>
    <div class="w3-container w3-section w3-border w3-card-2">
	    <div style="width:25%;float:left"><h3>Presets:</h3></div>
	    <div style="width:75%;float:left;padding-top:16px">
		    <div style="margin-bottom:15px" align="center">
		        <a class="w3-btn" href="index.php?totalbooks=500&numtitles=100&order=newscore&yearstart=1800&yearend=1899&gsdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&order=newscore" style="width:90%">19th Century</a>
		    </div>
    		<div style="margin-bottom:15px" align="center">
    		    <a class="w3-btn" href="index.php?totalbooks=500&numtitles=100&order=newscore&yearstart=1900&yearend=1999&gsdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&order=newscore" style="width:90%">20th Century</a>
    		</div>
    		<div style="margin-bottom:15px" align="center">
    		    <a class="w3-btn" href="index.php?totalbooks=500&numtitles=100&order=newscore&yearstart=1800&yearend=2016&gsdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&order=newscore" style="width:90%">1800 - Present</a>
    		</div>
    		<div style="margin-bottom:15px" align="center">
    			<a class="w3-btn" href="index.php?totalbooks=500&numtitles=100&order=newscore&yearstart=1800&yearend=2016&gsdata=0.0&langlitdata=0.0&alhdata=0.0&aldata=0.0&pdata=0.0&nbadata=0.0&nytdata=1&jstordata=0.0&startnumber=0&order=newscore&faulkner=yes" style="width:90%"><i>New York Times</i> Archive</a>
    		</div>
        </div>
    </div>
    <div class="w3-container w3-border w3-card-2" style="padding-bottom:16px;margin-bottom:8px">
        <h3>Custom</h3>
        <form id="generatorForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
        <div class="w3-row">
            <div class="w3-tooltip" style="width:100%">
		        <div style="width:75%;float:left;line-height:2;border-bottom:dashed 1px;border-color:#ccc">
			        <label class="w3-label">Max entries:
			        <span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:25;bottom:25px;width:250px">Enter the total number of books you'd like to have in your canon.</span></label>
		        </div>
        		<div style="width:25%;float:left">
        			<select class="w3-select-2" name="totalbooks">
        				<option value="100" <?php echo $totalbooks100; ?>>100</option>
        				<option value="250" <?php echo $totalbooks250; ?>>250</option>
        				<option value="500" <?php echo $totalbooks500; ?>>500</option>
        				<option value="1000" <?php echo $totalbooks1000; ?>>1000</option>
        			</select>
        		</div>
	        </div>
            <div style="width:100%">
        		<div style="width:75%;float:left;line-height:2;border-bottom:dashed 1px;border-color:#ccc">
        			<label class="w3-label">Entries per page:</label>
        		</div>
        		<div style="width:25%;float:left">
        			<select class="w3-select-2" name="numtitles">
        				<option value="100" <?php echo $selected100 ?>>100</option>
        				<option value="50" <?php echo $selected50 ?>>50</option>
        				<option value="25" <?php echo $selected25 ?>>25</option>
        			</select>
        		</div>
            </div>
            <div style="width:100%">
        		<div style="width:75%;float:left;line-height:2;border-bottom:dashed 1px;border-color:#ccc">
        			<label class="w3-label">Order by:</label>
        		</div>
        		<div style="width:25%;float:left">
        			<select class="w3-select-2" id="dropdown" name="order" onchange="orderfunction()">
        				<option value="newscore" <?php echo $selectedrank ?>>Score</option>
        				<option value="year" <?php echo $selectedyear ?>>Year</option>
        				<option value="title" <?php echo $selectedtitle ?>>Title</option>
        				<option value="fullname" <?php echo $selectedauthor ?>>Author</option>
        			</select>
        		</div>
            </div>
        </div>
        <hr>
        <div class="w3-row">
            <input class="w3-check" type="checkbox" name="oneperauth" value="true" <?php echo $oneauthcheck ?>>
            <label class="w3-validate w3-tooltip">Include only one work per author.
            <span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:0;bottom:25px;width:250px">Restrict the canon to only the highest scoring book by each author.</span></label>
        </div>
        <div>
            <input class="w3-check" type="checkbox" name="womenonly" value="true" <?php echo $womenonlycheck ?>>
            <label class="w3-validate">Include only books written by women.</label>
        </div>
        <hr>
        <div>Include only:</div>
		<?php
			$user_level = UserHandling::getUserLevel($user);
			echo(HTMLGenerator::getGenreMenu($user_level,$genres,$included_genres));
		?>
        <hr>
        <div style="margin-bottom:15px" align="center">
            <button class="w3-btn" onclick="orderfunction()" style="width:90%">Generate Canon</button>
        </div>
        <div class="w3-accordion" align="center">
            <div id="moreoptions" class="w3-btn-block" onclick="accordion('accordion')" style="width:90%">Show More Options</div>
            <div id="accordion" class="w3-accordion-content" align="left">
            <hr>
                <div>
                    Include only works published between <input class="w3-border-bottom" type="text" name="yearstart" style="border:none;width:50px" value="<?php echo $yearstart; ?>"> and <input type="text" name="yearend" class="w3-border-bottom" style="border:none;width:50px" value="<?php echo $yearend; ?>">.
                </div>
                <hr>
                <div class="w3-tooltip"><h3>Althorithm Modifications</h3>
                    <span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:0;bottom:25px;width:250px">Change the weight of these data points in order to change the shape of your canon.</span>
				</div>
                <?php WeightSelectionModule(''); ?>
                <hr>
                <div style="margin-bottom:15px" align="center">
                    <button class="w3-btn" onclick="orderfunction()" style="width:90%">Generate Canon</button>
                </div>
                <hr>
                <div id="faulkner">
                    <img id="faulknerno" src="<?php if ($faulkner == 'yes') {echo 'images/nofaulknersmallquest.png';} else {echo 'images/nofaulknersmallquestchecked.png';} ?>" class="bottom" onclick="noFaulkner()">
                    <img id="faulkneryes" src="<?php if ($faulkner == 'yes') {echo 'images/nofaulknersmall.png';} else {echo 'images/nofaulknersmallchecked.png';} ?>" class="top" onclick="noFaulkner()">
                </div>
        </div>
    </div>
		
			<input id="startingentry" type="hidden" name="startnumber" value="0" />
			<input id="option" type="hidden" name="order" value="">
			<input id="nofaulkner" type="hidden" name="faulkner" value="<?php echo $faulkner; ?>"/>
		</form>
		<form id="markasreadform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<input id="markasreadvalue" type="hidden" name="markasread" value=""/>
			<input id="markasunreadvalue" type="hidden" name="markasunread" value=""/>
		</form>

	</div>
</div>

<div class="w3-col l8 s12" style="padding-right:8px;padding-left:8px">
    <div class="w3-container w3-border w3-card-2" style="margin-top:8px;margin-bottom:16px">
        <div class="w3-row">
            <div class="w3-container w3-col l1 s12"></div>
            <div class="w3-container w3-col l10 s12" style="text-align: center;"><h1>AMERICAN FICTION <?php if ($yearstart == $yearend) {echo $yearstart ;} else {echo $yearstart.'-'.$yearend;} ?></h1></div>
            <div class="w3-container w3-col l1 s12" style="padding:16px">
                <?php if(!$id):?>
                	<a href="#" onclick="document.getElementById('modal1').style.display='block'" class="w3-tooltip">
                <?php else: ?>
                	<a href="#" onclick="document.getElementById('modal2').style.display='block'" class="w3-tooltip">
                <?php endif;?>
                <img src="images/metacanonsaveicon16.png" alt="Save"><span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;right:-45px;top:25px;width:150px">Save this setting as a user preset.</span></a>
            </div>
        </div>
		<div class="w3-container w3-border-bottom w3-row" style="text-align: center;">
			<p id="pagination1"></p>
			<script>resultsFunction('pagination1',<?php echo $fictionCount; ?>,<?php echo $totalbooks; ?>,<?php echo $numtitles; ?>,<?php echo $startnumber; ?>);</script>
		</div>
		
		<table class="w3-table w3-bordered desktoponly">
		    <tr>
    			<td style="font-weight: bold; width: 5%;">#</td>
    			<td style="font-weight: bold; width: 5%;" align="right"></td>
    			<td style="width: 230px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="bottle(); submitform();" id="title">Title</a></td>
    			<td style="width: 140px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="author(); submitform();" id="author">Author</a></td>
    			<td style="width: 75px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="year(); submitform();" id="year">Year</a></td>
    			<td style="width: 75px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="score(); submitform();" id="score">Score</a></td>
		    </tr>
		    <?php include 'book_table_desktop.php'; ?>
		</table>
		
		<table class="w3-table w3-bordered mobileonly">
		    <tr>
    			<td style="font-weight: bold; width: 5%;">#</td>
    			<td style="font-weight: bold; width: 5%;" align="right"></td>
    			<td></td>
    			<td style="width: 75px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="score(); submitform();" id="score">Score</a></td>
		    </tr>
		    <?php include 'book_table_mobile.php'; ?>
		</table>
		
		<div class="w3-container" style="text-align: center;">
			<p id="pagination2"></p>
			<script>resultsFunction('pagination2',<?php echo $fictionCount; ?>,<?php echo $totalbooks; ?>,<?php echo $numtitles; ?>,<?php echo $startnumber; ?>);</script>
		</div>
	</div>
</div>

<!-- User Not Logged In Popup -->
<div id="modal1" class="w3-modal">
    <div class="w3-modal-content w3-card-8 w3-animate-right">
        <header class="w3-container w3-border" style="background:#eee;height:35px">
            <span onclick="document.getElementById('modal1').style.display='none'" class="w3-closebtn">&times;</span>
        </header>
        <div class="w3-container w3-border" align="center">
            <h2>You must be logged in to do that.</h2><br>
            <a href="userlogin.php" class="w3-btn w3-large">Click here to log in or register.</a>
            <hr>
        </div>
    </div>
</div>

<!-- Save User Preset -->
<form id="savepreset" action="userlogin.php" method="post">
    <div id="modal2" class="w3-modal w3-small">
        <div class="w3-modal-content w3-card-8 w3-animate-right">
            <header class="w3-container w3-border" style="background:#eee;height:35px">
                <span onclick="document.getElementById('modal2').style.display='none'" class="w3-closebtn">&times;</span>
            </header>
			<div class="w3-container w3-border" style="padding-top:16px">
				<div class="w3-row">
					<div class="w3-tooltip w3-third w3-container">
					<label class="w3-label">Max entries:
					<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:25;bottom:25px;width:250px">Enter the total number of books you'd like to have in your canon.</span></label>
					<select class="w3-select" name="totalbooks">
					<option value="100" <?php echo $totalbooks100; ?>>100</option>
					<option value="250" <?php echo $totalbooks250; ?>>250</option>
					<option value="500" <?php echo $totalbooks500; ?>>500</option>
					<option value="1000" <?php echo $totalbooks1000; ?>>1000</option>
					</select><br>
					</div>
					<div class="w3-third w3-container">
						<label class="w3-label">Entries per page:</label>
						<select class="w3-select" name="numtitles">
							<option value="100" <?php echo $selected100 ?>>100</option>
							<option value="50" <?php echo $selected50 ?>>50</option>
							<option value="25" <?php echo $selected25 ?>>25</option>
						</select><br>
					</div>
					<div class="w3-third w3-container">
						<label class="w3-label">Order by:</label>
						<select class="w3-select" name="order">
							<option value="newscore" <?php echo $selectedrank ?>>Score</option>
							<option value="year" <?php echo $selectedyear ?>>Year</option>
							<option value="title" <?php echo $selectedtitle ?>>Title</option>
							<option value="fullname" <?php echo $selectedauthor ?>>Author</option>
						</select>
					</div>
				</div>
				<hr>
				<div class="w3-col l4 s12" style="padding-left:8px;padding-top:5px;padding-bottom:16px;">
					<div>
						<div>  
							<input class="w3-check" type="checkbox" name="oneperauth" value="yep" <?php echo $oneauthcheck ?>>
							<label class="w3-validate w3-tooltip">Include only one work per author.
								<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:0;bottom:25px;width:250px">Restrict the canon to only the highest scoring book by each author.</span>
							</label>
						</div>
						<div>
							<input class="w3-check" type="checkbox" name="womenonly" value="yep" <?php echo $womenonlycheck ?>>
							<label class="w3-validate">Include only books written by women.</label>
						</div>
					</div>
					<hr>
					<div>
						<div>Include only:</div>
						<?php
							$user_level = UserHandling::getUserLevel($user);
							echo(HTMLGenerator::getGenreMenu($user_level,$genres,$included_genres));
						?>
					</div>
					<hr>
					<div>
						Include only works published between <input class="w3-border-bottom" type="text" name="yearstart" value="<?php echo $yearstart; ?>" style="border:none;width:32px"> and <input class="w3-border-bottom" type="text" name="yearend" value="<?php echo $yearend; ?>" style="border:none;width:32px">.
					</div>
				</div>
				<div class="w3-col l4 s12" style="padding-top:0px;padding-left:8px;padding-right:8px;padding-bottom:8px" align="right">  
					<div class="w3-tooltip"><h4 align="left">Althorithm Modifications</h4>
						<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:0;bottom:25px;width:250px">Change the weight of these data points in order to change the shape of your canon.</span>
					</div>
					<?php WeightSelectionModule('style="Width:70px"'); ?>
				</div>
				<div class="w3-col l4 s12" style="padding-left:8px;padding-right:8px;">  
					<input id="startingentry" type="hidden" name="startnumber" value="0" />
					<label class="w3-label">Faulkner?</label>
					<input type="radio" name="faulkner" value="yes" <?php if ($faulkner =='yes') {echo 'checked';} ?> >Yes
					<input type="radio" name="faulkner" value="no" <?php if ($faulkner =='no') {echo 'checked';} ?> >No
					<br><br>
					<div class="w3-tooltip">
						<input class="w3-input" type="text" name="presetname" placeholder="Preset Title" style="width:100%"></input>
						<span class="w3-text w3-tag w3-pale-yellow w3-border" 
							style="position:absolute;left:25;bottom:25px;width:250px">Enter a title for your preset. Your saved presets will appear on your user page.</span>
					</div>
					<div class="w3-section"><input class="w3-btn" type="submit" value="Save Preset" style="width:100%"></input></div>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- n-gram Frequency Correction Popup -->
<?php include 'ngramfreq.php'; ?>

<!-- footer -->
<?php include 'footer.php'; ?>

</body>
</html>

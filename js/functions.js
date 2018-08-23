$(document).ready(function()
	{
		console.log("ready!");
	}
);

//create popout status menus for each book in the list	
$(document).ready(function()
{
	rownumber = 1;
	while (rownumber < 101)
	{
		$("#hiddenrow" + rownumber).hide();
		rownumber += 1;
	}
	
	function hidethem(a)
	{
		$("#row" + a).click(function()
		{
		        $("#hiddenrow" + a).toggle();
		});
	}
	
	var myNumbers = [];
	for (var i = 1; i <101; i++)
	{
		myNumbers.push(i);
	}

	myNumbers.forEach(hidethem);	
});
	
//create popout status menus for each book in the mobile list	
$(document).ready(function(){
	rownumber = 1;
	while (rownumber < 101){
	$("#hiddenrow" + rownumber + "m").hide();
	rownumber += 1;
	}	
	
	function hidethem(a){
	$("#row" + a + "m").click(function(){
        $("#hiddenrow" + a + "m").toggle();
    });
	}
	
	var myNumbers = [];
	for (var i = 1; i <101; i++){
		myNumbers.push(i);
	}
	myNumbers.forEach(hidethem);
	
}); 


//add submit form function to page navigation
function submitform() { document.forms["generatorForm"].submit(); }

function bottle() { document.getElementById("option").value = "title"; }
function score() { document.getElementById("option").value = "newscore"; }
function year() { document.getElementById("option").value = "year"; }
function author() { document.getElementById("option").value = "fullname"; }

function orderfunction()
{
	var x = document.getElementById("dropdown");
	var y = x.options[x.selectedIndex].value;
	document.getElementById("option").value = y;
}

//highlight the active column heading in red
$(document).ready(function(){
	order = "<?php echo ($order); ?>" ;

	if (order == "year")
	{ document.getElementById("year").style = 'color: red;';}
	if (order == "title")
	{ document.getElementById("title").style = 'color: red;';}
	else {}
	if (order == "fullname")
	{ document.getElementById("author").style = 'color: red;';}
	else {}
	if (order == "newscore DESC")
	{ document.getElementById("score").style = 'color: red;';}
	else {}
});

function markasread(a) {
	document.getElementById("mark" + a).innerHTML = '&#8987;';
	document.getElementById("mark" + a).style.color = "#dddddd";
	document.getElementById("mmark" + a).innerHTML = '&#8987;';
	document.getElementById("mmark" + a).style.color = "#dddddd";
	
	$.post("markasread.php",{markread: a},function(data, status){
        
		// Uncomment the alert to get feedback:
		// alert("Data: " + data + "\nStatus: " + status);
		document.getElementById("mark" + a).innerHTML = '&#x1f453;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as unread.</span>';
		document.getElementById("mark" + a).style.color = "black";
		document.getElementById("mark" + a).onclick = function(){markasunread(a);};
		
		document.getElementById("mmark" + a).innerHTML = '&#x1f453;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as unread.</span>';
		document.getElementById("mmark" + a).style.color = "black";
		document.getElementById("mmark" + a).onclick = function(){markasunread(a);};
	});
}

function markasunread(a) {
document.getElementById("mark" + a).innerHTML = '&#8987;';
document.getElementById("mark" + a).style.color = "#dddddd";
document.getElementById("mmark" + a).innerHTML = '&#8987;';
document.getElementById("mmark" + a).style.color = "#dddddd";

$.post("markasread.php",{markasunread: a},function(data, status){
		// Uncomment the alert to get feedback:
		// alert("Data: " + data + "\nStatus: " + status);
		document.getElementById("mark" + a).innerHTML = '&#9711;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as read.</span>';
		document.getElementById("mark" + a).style.color = "#dddddd";
		document.getElementById("mark" + a).onclick = function(){markasread(a);};
		
		document.getElementById("mmark" + a).innerHTML = '&#9711;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as read.</span>';
		document.getElementById("mmark" + a).style.color = "#dddddd";
		document.getElementById("mmark" + a).onclick = function(){markasread(a);};
    });
}

function noFaulkner() {
    if (document.getElementById("nofaulkner").value == "true") {
    	document.getElementById("nofaulkner").value = "false";
    	document.getElementById("faulknerno").src = "images/nofaulknersmallquestchecked.png";
    	document.getElementById("faulkneryes").src = "images/nofaulknersmallchecked.png";
    }
    else {
    	document.getElementById("nofaulkner").value = "true";
    	document.getElementById("faulknerno").src = "images/nofaulknersmallquest.png";
    	document.getElementById("faulkneryes").src = "images/nofaulknersmall.png";
    }
}

function accordion(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
    var y = document.getElementById("moreoptions").innerHTML;
    if (y == "Show More Options")
    {document.getElementById("moreoptions").innerHTML = "Hide Advanced Options";}
    else
    {document.getElementById("moreoptions").innerHTML = "Show More Options";}
}

function one(a) {
    document.getElementById("startingentry").value = a;
}

function resultsFunction(element,totalresults,totalbooks,resultsperpage,startnumber) {
	if (totalresults > totalbooks)
	{totalresults = totalbooks ;}
	var pagelinknumber = 0;
	var totalpages = totalresults/resultsperpage;
	var text = "";
	var pagenumber = startnumber/resultsperpage;
	var elipsis1 = 0;
	var elipsis2 = 0;
	while (pagelinknumber < (totalpages-1))	
	{
	if (pagelinknumber != 0 && totalpages > 10 && (pagelinknumber > pagenumber + 3 || pagelinknumber < pagenumber - 3))	
		{if (elipsis1 == 0 && pagelinknumber > pagenumber + 3)
		  {text += " ... ";}
		  if (pagelinknumber > pagenumber +3)
		  {elipsis1 ++;}
		  if (elipsis2 == 0 && pagelinknumber < pagenumber - 3)
		  {text += " ... ";}
		  if (pagelinknumber < pagenumber - 3)
		  {elipsis2 ++;}
		}
	else if (pagelinknumber*resultsperpage == startnumber)
	{ text += "<span style='color: red;'>(" + (1 + pagelinknumber*resultsperpage) + "-" + (pagelinknumber*resultsperpage + resultsperpage) + ")</span> " }
	else {text += "<a href='#' onclick='one(" + pagelinknumber*resultsperpage + "); orderfunction(); submitform();'>(" + (1 + pagelinknumber*resultsperpage) + "-" + (pagelinknumber*resultsperpage + resultsperpage) + ")</a> ";}
	pagelinknumber ++;
	}
	
	if (pagelinknumber*resultsperpage == startnumber)
	{text += "<span style='color: red;'>(" + (1 + pagelinknumber*resultsperpage) + "-" + (pagelinknumber*resultsperpage + (totalresults - pagelinknumber*resultsperpage)) + ")</span>";}
	else
	{text += "<a href='#' onclick='one(" + pagelinknumber*resultsperpage + "); orderfunction(); submitform();'>(" + (1 + pagelinknumber*resultsperpage) + "-" + (pagelinknumber*resultsperpage + (totalresults - pagelinknumber*resultsperpage)) + ")</a>";}
	document.getElementById(element).innerHTML = text;
}

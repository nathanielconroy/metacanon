<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">

<body>

<?php include 'header.php'; ?>

<div>

<div style="padding:8px">
<div class="w3-container w3-border w3-card-2" style="margin-bottom:8px">

<h2>What is Metacanon?</h2>


<p>Metacanon is an interactive canon generator. You can use this as a more flexible and inclusive alternative to the various &quot;greatest books&quot; lists published by entities like Modern Library. The current version of Metacanon only includes American fiction, but future versions will be expanded to include other genres, periods and national literatures. The current list of 1000 works is based on a master list of over 2500 American novels and other works of fiction.</p>



<h2>How do I use it?</h2>


<p>By default, Metacanon displays a list of the 500 highest scoring American novels and short story collections of the twentieth century. However, you can use 
the left hand menu to change the parameters of the canon in order to generate other custom lists. For example, you could create a <a href="index.php?totalbooks=500&numtitles=100&order=score&yearstart=1950&yearend=1959&gsdata=0.0&langlitdata=0.0&alhdata=0.0&aldata=0.0&pdata=0.0&nbadata=0.0&nytdata=1&jstordata=0.0&startnumber=0&order=score&faulkner=yes" target="_blank"> list of the top works 
published between 1950 and 1959 according to citation frequency in the <i>New York Times</i> archive.</a> Alternatively, you could create a list of <a href="index.php?totalbooks=500&numtitles=100&order=score&womenonly=yep&novels=yep&yearstart=1880&yearend=1980&gsdata=1&langlitdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&jstordata=0.0&startnumber=0&order=score&faulkner=yes" target="_blank">novels written by women between 1880 and 1980.</a> </p>

<h2>What does it measure?</h2>


<p>Because this list is based primarily on the number of citations that works have received in scholarly journals,
it would be a mistake to presume that it measures "greatness" or
aesthetic value. It would be more accurate to say that it measures
"significance," i.e. the extent to which a particular work has been the
object of scrutiny by scholars and awards committees. This indicates
nothing more than that the works on this list have been discussed frequently. This is exponentionally true of the books toward the top of the list.
However, their&nbsp;merits as works of art are a matter of ongoing debate.
Thus while many of the works on this list are likely to also appear on greatest books lists, others are not. For example, few would argue that Thomas Dixon&#39;s
racist novel <i>The Clansmen</i> is a great work of art, but since it has been discussed frequently (if unfavorably) in literary scholarship, it currently has a score high enough to place it within the top 200 books. For better or for worse, Metacanon does not distinguish between different forms of critical significance, whether they be aesthetic, historical, political, etc.</p>






<h2>How are the scores calculated?</h2>

<p>1. Works awarded the Pulitzer Prize for fiction are given 1 point.<br>
2. Works awarded the National Book Award for fiction are given 1 point.<br>
3. Finalists for either the Pulitzer or the National Book Award are given 0.5 points.<br>
4. Each work is then given a weighted score based on the number of citations it has received in
Google Scholar.<br>
5. A second score is given for the number of citations it
has received in <a href="www.jstor.org">JSTOR</a>.<br>
6. Each work is also given a weighted score based
on the number of citations it has received in two major journals of
Americanist literary criticism: <i>American Literature</i> and <i>American Literary History</i>.<br>


<p>Here is a typical example:</p>

<p><i>Invisible Man</i>
by Ralph Ellison receives 1 point for winning the National Book Award, 10.19 points for having 2185 Google Scholar citations, 5.98 points for having 1183 search results on JSTOR, 3.48 points for having 45 search results in <i>American Literature</i>, and 5.50 points for having 52 search results in <i>American Literary History</i>. When these
points are added together, they create a combined score of 26.15. This
places <i>Invisible Man </i>at number 2 on the list, just below <i>Beloved </i>(27.15 points) and just above <i>The Great Gatsby </i>(23.31 points).</p> 

<p>However, you can also use the custom menu on the left hand side of the home page to alter this algorithm, either by changing the weight accorded to any of these sources, or by adding in data from other sources (for example, the <i>New York Times</i> archive). In doing so, you will change the score each work receives as well as the general order of the list. You can then save your newly altered algorith as a custom preset.</p>

<p>Click <a href="http://metacanon.org/blog/uncategorized/the-metacanon-algorithm-explained/" target="_blank">(here)</a> for a more detailed explanation of the Metacanon algorithm.</p>

<h2>How does Metacanon define &quot;fiction&quot;?</h2>

<p>For the purposes of this project "fiction" refers to novels, collections of short stories, novellas, and any other work of book-length prose commonly designated as fiction. Individual short stories are not included. </p>

<p>Metacanon also leaves out memoirs, autobiographies, and other works of creative nonfiction. Since the boundary between "fiction" and "nonfiction" will always be blurry, this is a somewhat arbitrary decision. At least some definitions of fiction would include books like <i>Let Us Now Praise Famous Men</i>, and <i>The Woman Warrior</i>, both of which are excluded here. Equally problematic is the genre of the "nonfiction novel," often used to categorize books like <i>In Cold Blood</i> and <i>The Executioner's Song</i>. </p>

<p>For now, I've decided (again somewhat arbitrarily) to leave all of these works off of the list. This is only because it would be a lot harder to be sure to include every nonfiction-but-actually-sort-of-like-fiction book written by an American (at least for now) than to just narrow the definition of "fiction" so that it excludes these borderline cases.</p>

<p>In a later version of Metacanon, users will be able to decide whether they want to see a list of 20th century prose literature (including <i>Woman Warrior </i>etc.), or just "20th century fiction." This still won't get rid of the problem of working with some pretty contestable categories, but it will at least allow for more flexibility.</p>

<h2>How does Metacanon define &quot;American&quot;?</h2>

<p>"American" is arguably an even more problematic category than "fiction," as laden as it is with heavy nationalist and imperialist baggage. How do we decide if a novel is "American"? Does the author have to be American? If so, how do we actually know who counts as "American?" Do they have to have been born in the United States? Or is having lived in the U.S. for a certain period of time enough? If so, how long? What about works written by authors living in the U.S. territory of Puerto Rico? What if those works are written in Spanish? What about works written in Canada or Mexico?</p>

<p>Rather than try to solve this problem, Metacanon merely replicates the common practices of scholars in the field of American literary studies. Generally speaking, works of fiction are usually called "American" if they are written by someone who was born in the United States (usually including Puerto Rico) or if they are written by an immigrant to the United States. Following this loose consensus (and it is <i>quite </i>loose), the novels of William Faulkner and Toni Morrison are "American," just as are the novels of Henry James (even the ones he wrote after crossing the Atlantic), as are the novels that Vladimir Nabokov wrote in the United States (but not his early works). While being written in English isn't a strict qualification, most of the works on this list meet it.</p>

<p>There is a certain imperialism to this definition. Americaness is constantly being exported around the globe by expatriates even as the works of any author who comes to the United States are immediately claimed as products of American culture. The writings of Canadians, Central Americans, and South Americans are generally excluded&mdash;unless, of course, these authors happen to spend a significant amount of time in the United States, at which point their works are almost magically transmuted into products of the American experience.</p>

<p>This list doesn't explicitly challenge this definition&mdash;as problematic as it is. However, I hope that having a list like this does at least make its contours slightly more legible. </p>

<p>Future versions of Metacanon will allow users to customize this definition dynamically, for example, by including only novels actually written in the United States (sorry James) or by including novels written anywhere on the North American continent or anywhere in the Americas more generally. In addition, users will have the option to build custom lists based on criteria other than national belonging.</p>

<h2>Who is behind this project?</h2>
<p>Metacanon was designed by Nathaniel Conroy, a recent Ph.D. from the English department at Brown University and a current Scholar in Residence in the Literature Department at American University in Washington, DC.</p>

<h2>What if I notice something that's missing?</h2>

<p>If you think there's a book that should be included here but isn't, you can contact me by sending an email to <a href="mailto:nathanielaconroy@gmail.com">nconroy@american.edu</a> or by tweeting me <a href="https://twitter.com/ConroyNathaniel">@ConroyNathaniel</a>.</p>


</div>
</div>
</div>


<?php include 'footer.php'; ?>

</body>
</html>

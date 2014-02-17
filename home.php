<?php
 require "classfun.php";   # gets functions we will use
 require "mydb.php"; # gets function we will use to connect to database
 ## call a method to output the document's page heading
 printDocHeading("./style.css", "SoccerSim Homepage");
 print "<body>\n";
 ## if nothing has been posted to the page (first time page is being loaded)
 if (empty($_POST)) 
 {	
 	viewPage();
	#viewTeams();
 } // end if 

 function viewPage()
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Welcome to SoccerSim! </h1>\n";
 	$image = "greenSoccerPitch.png";
 	$width = 640;
 	$height = 360;
 	echo '<p><img src ="'.$image.'" style = width:"'.$width.'px;height:'.$height.'px;"></p>'; 
 	print "</div>\n"; 
 } // end viewPage()

 function viewTeams()
 {
 	print "<div class = 'content'>\n";
	print "<h3> Select Teams: </h3>\n";
 } // end viewTeams()
?>

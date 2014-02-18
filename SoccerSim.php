<?php
//--------------- Document heading , style sheet, title --------------//
 require "classfun.php";   # gets functions we will use
 require "mydb.php"; # gets function we will use to connect to database
 ## call a method to output the document's page heading
 printDocHeading("./style.css", "SoccerSim Homepage");
 print "<body>\n";
 ## if nothing has been posted to the page (first time page is being loaded)
 if (empty($_POST)) 
 {	
 	viewPage();
 } // end if 
 else if ($_POST['Submit_Game'])
 {
 	viewTeams();
 } // end else if
 else if ($_POST['Submit_Team'])
 {
 	viewPlayers();
 } // end else if
 printDocFooter();

 //-----------------FUNCTIONS-------------------//

 function viewPage()
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Welcome to SoccerSim! </h1>\n";
 	$image = "greenSoccerPitch.png";
 	$width = 640;
 	$height = 360;
 	echo '<p><img src ="'.$image.'" style = width:"'.$width.'px;height:'.$height.'px;"></p>';
 	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n"; 
 	print "<p><input type = 'submit' value = 'Play' name = 'Submit_Game'/>\n"; 
	print "</p>\n";
	print "</form>\n";
	print "</div>\n"; 
 } // end viewPage()

function viewTeams()
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Select Teams </h1>\n";
 	print "<div class = 'content2'>\n";	
	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
	print "<p><select name = 'Teams'>\n";
	$db = adodbConnect();
	$query = "select * from Teams";
	$result = $db -> Execute($query);
	while ($row = $result -> FetchRow())
	{
		$team = $row['Team_ID'];
		$desc = $row['Team_Name'];
		print "<option value = '$team'>"."$desc</option>";
	}// end while
	print "</select>\n";
	print "</p>\n";
	print "<p><input type = 'submit' value = 'Submit' name = 'Submit_Team'/>\n";
	print "</p>\n";
	print "</form>\n";
	print "</div>";
	print "</div>";
 } // end viewTeams()

 function viewPlayers()
 {
 	print "<div class = 'content'>\n";
 	print "<h1> View Players </h1>\n";
 	print "<div class = 'content2'>\n";	
	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
	print "<p><select name = 'Player'>\n";
	$db = adodbConnect();
	$query = "select * from Player";
	$result = $db -> Execute($query);
	while ($row = $result -> FetchRow())
	{
		$player = $row['Player_ID'];
		$name = $row['Name'];
		$pos = $row['Position'];
		print "<option value = '$player'>"."$name"."$pos</option>";
	}// end while
	print "</select>\n";
	print "</p>\n";
	print "<p><input type = 'submit' value = 'Submit' name = 'Submit_Team'/>\n";
	print "</p>\n";
	print "</form>\n";
	print "</div>";
	print "</div>";
 } // end viewPlayers()
?>

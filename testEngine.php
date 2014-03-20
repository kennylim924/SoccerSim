<?php
 //---------------Document heading , style sheet, title--------------//
 require "classfun.php";   # gets functions we will use
 require "mydb.php"; # gets function we will use to connect to database
 ## call a method to output the document's page heading
 printDocHeading("./style2.css", "Engine Testing");
 print "<body>\n";
 ## if nothing has been posted to the page (first time page is being loaded)
 if (empty($_POST)) 
 {	
 	viewPage();
	#displayTime();
 }
 if ($_POST['Submit_Game'])
 {
	viewTeams();
 }
 if ($_POST['Submit_Team'])
 {
	$team = $_POST['Teams'];
	$team2 = $_POST['Teams2'];
	viewPlayers($team, $team2);
 } // end if
 if ($_POST['Submit_Players'])
 {
 	$team = $_POST['Players'];
 	$team2 = $_POST['Players2'];
 	teamStats($team, $team2);
 } // end if 
 if ($_POST['Team_Stats_Cont'])
 {
	playGame();
 }
 else if ($_POST['Home'])
 {
 	viewPage();
 } // end else if
 printDocFooter();

 //---------------------------Functions------------------------//

 function displayTime()
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Game Minutes: </h1>\n";
 	print "<div class = 'content4'>\n";
 	$minutes = range("1", "45", 1);
 	foreach ($minutes as $minute) 
 	{
 		print '<pre>'; #<pre></pre> confuses browser to print array line by line
 		print_r($minute.'min'.' '.'-'.' '.'some event'."\n");
 		print '</pre>';
 	} // end foreach loop
 	print "</div>";
	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n"; 
 	print "<p><input type = 'submit' value = 'Play' name = 'Submit_Game'/>\n"; 
	print "</p>\n";
	print "</form>\n";
	print "</div>";
 } // end function displayTime()
 
 // function will display home page along with an image
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

 // function called after 'Play' button is pushed
 // will query the database to populate teams 
 function viewTeams()
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Select Teams </h1>\n";
 	print "<div class = 'content2'>\n";
	print "<div class = 'content_left'>\n";	
	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
	print "<p><select name = 'Teams'>\n";
	$db = adodbConnect();
	// if connection to database is not valid, print this message
	if(!$db)
	{
		print "Failed attempt: No connection to Database\n";
		return;
	} // end if
	$query = "select * from Teams order by Team_Name asc";
	$result = $db -> Execute($query);
	while ($row = $result -> FetchRow())
	{
		$team = $row['Team_ID'];
		$desc = $row['Team_Name'];
		print "<option value = '$team'>"."$desc</option>";
	}// end while
	print "</select>\n";
	print "</p>\n";
	print "</div>\n";

	//---------------Query databse for 2nd team---------------//

	print "<div class = 'content_right'>\n";	
	print "<p><select name = 'Teams2'>";
	$db = adodbConnect();
	// if connection to database is not valid, print this message
	if(!$db)
	{
		print "Failed attempt: No connection to Database\n";
		return;
	} // end if
	$query2 = "select * from Teams order by Team_Name asc";
	$result = $db -> Execute($query2);
	while ($row = $result -> FetchRow())
	{
		$team2 = $row['Team_ID'];
		$desc2 = $row['Team_Name'];
		print "<option value = '$team2'>"."$desc2</option>";
	}// end while
	print "</select>";
	print "</p>";
	print "</div>";
	print "</br>\n";
	print "</br>\n";
	print "<div class = 'button_submit'>";
	print "<input type = 'submit' value = 'Submit' name = 'Submit_Team'/>\n";
	print "</div>\n";
	print "</form>";
	print "</div>";
	print "</div>";
 } // end viewTeams()

 // function is called after teams are selected
 // variables $team and $team2 are passed to this function from viewTeams() 
 // variables determine which team is selected in order to populate the players for specific team
 function viewPlayers($team, $team2)
 {
 	print "<div class = 'content'>\n";
 	print "<h1> View Players </h1>\n";
 	print "<div class = 'content_players'>\n";	
 	print "<div class = 'content_left2'>\n";
	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
	print "<p><select name = 'Players'>\n";
	$db = adodbConnect();
	// if connection to database is not valid, print this message
	if(!$db)
	{
		print "Failed attempt: No connection to Database\n";
		return;
	} // end if
	$query = "select * from Players where Team_ID = '$team'";
	$result = $db -> Execute($query);
	while ($row = $result -> FetchRow())
	{
		$team = $row['Team_ID'];
		$player = $row['Player_ID'];
		$name = $row['Name'];
		$pos = $row['Position'];
		$rating = $row['Rating'];
		print "<option value = '$team'>".$player."&nbsp&nbsp&nbsp".$name."&nbsp&nbsp".$pos."&nbsp&nbsp&nbsp&nbsp$rating</option>";
	}// end while
	print "</select>\n";
	print "</p>\n";
	print "</div>\n";

	//----------------------Query Database for 2nd team players-----------------//

	print "<div class = 'content_right2'>\n";
	print "<p><select name = 'Players2'>\n";
	$db = adodbConnect();
	// if connection to database is not valid, print this message
	if(!$db)
	{
		print "Failed attempt: No connection to Database\n";
		return;
	} // end if
	$query2 = "select * from Players where Team_ID = '$team2'";
	$result = $db -> Execute($query2);
	while ($row = $result -> FetchRow())
	{
		$team2 = $row['Team_ID'];
		$player2 = $row['Player_ID'];
		$name2 = $row['Name'];
		$pos2 = $row['Position'];
		$rating2 = $row['Rating'];
		print "<option value = '$team2'>".$player2."&nbsp&nbsp&nbsp".$name2."&nbsp&nbsp".$pos2."&nbsp&nbsp&nbsp&nbsp$rating2</option>";
	}// end while
	print "</select>";
	print "</p>";
	print "</div>";
	print "</br>\n";
	print "</br>\n";
	print "<div class = 'button_submit2'>";
	print "<p><input type = 'submit' value = 'Continue' name = 'Submit_Players'/>";
	print "</p>\n";
	print "</div>\n";
	print "</form>";
	print "</div>";
	print "</div>";
 } // end viewPlayers()

 // function is called after continuing from viewPlayers() page
 // function is pased $team and $team variable as well
 // the variables determine the teams selected and alows for calculation of avgerages for specific team
 // averages include: defense, attacking, along with GK
 function teamStats($team, $team2)
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Team Stats </h1>\n";
 	print "<div class = 'content_stats'>\n";
 	print "<div class = 'content_left2'>\n";
 	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
 	$db = adodbConnect();
 	// query the DB for the 'DEF' enum
 	$queryD = "select AVG(Rating) from Players where Team_ID = '$team' and Position = 'DEF'";
 	$result = $db -> Execute($queryD);
 	while ($row = $result -> FetchRow())
 	{
 		$avgRate_D = $row['AVG(Rating)'];
 		print "Defensive Average: ";
 		print number_format($avgRate_D + 0, 1);  
 	} // end while
 	print "\n";
 	print "<p>\n";
 	print "</p>\n";
 	// query the DB for the 'ATT' enum
 	$queryA = "select AVG(Rating) from Players where Team_ID = '$team' and Position = 'ATT'";
 	$result = $db -> Execute($queryA);
 	while ($row = $result -> FetchRow())
 	{
 		$avgRate_A = $row['AVG(Rating)'];
 		print "Attacking Average: ";
 		print number_format($avgRate_A + 0, 1);  
 	} // end while
 	print "\n";
 	print "<p>\n";
 	print "</p>\n";
 	// query the DB for 'GK' enum
 	$queryG = "select Rating from Players where Team_ID = '$team' and Position = 'GK'";
 	$result = $db -> Execute($queryG);
 	while ($row = $result -> FetchRow())
 	{
 		$avgRate_G = $row['Rating'];
 		print "Goalkeeper: ";
 		print $avgRate_G + 0;  
 	} // end while
 	print "</div>\n";

 	//------------------2nd set of queries for 2nd team--------------//

 	print "<div class ='content_right2'>\n";
 	$db = adodbConnect();
 	// query the DB for the 'DEF' enum for 2nd team
 	$queryD2 = "select AVG(Rating) from Players where Team_ID = '$team2' and Position = 'DEF'";
 	$result = $db -> Execute($queryD2);
 	while ($row = $result -> FetchRow())
 	{
 		$avgRate_D2 = $row['AVG(Rating)'];
 		print "Defensive Average: ";
 		print number_format($avgRate_D2 + 0, 1);  
 	} // end while
 	print "\n";
 	print "<p>\n";
 	print "</p>\n";
 	// query the DB for the 'ATT' enum for 2nd team
 	$queryA2 = "select AVG(Rating) from Players where Team_ID = '$team2' and Position = 'ATT'";
 	$result = $db -> Execute($queryA2);
 	while ($row = $result -> FetchRow())
 	{
 		$avgRate_A2 = $row['AVG(Rating)'];
 		print "Attacking Average: ";
 		print number_format($avgRate_A2 + 0, 1);  
 	} // end while
 	print "\n";
 	print "<p>\n";
 	print "</p>\n";
 	// query the DB for 'GK' enum for 2nd team
 	$queryG2 = "select Rating from Players where Team_ID = '$team2' and Position = 'GK'";
 	$result = $db -> Execute($queryG2);
 	while ($row = $result -> FetchRow())
 	{
 		$avgRate_G2 = $row['Rating'];
 		print "Goalkeeper: ";
 		print $avgRate_G2 + 0;  
 	} // end while
 	print "</div>\n";
 	print "</br>\n";
 	print "</br>\n";
 	print "</br>\n";
 	print "</br>\n";
 	print "<div class = 'button_submit'>\n";
 	print "<p><input type = 'submit' value = 'Continue' name = 'Team_Stats_Cont'/>\n";
	print "</p>\n";
	print "</div>\n";
 	print "</form>\n";
 	print "</div>";
 	print "</div>";
 } // end teamStats()

 function playGame()
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Game Session </h1>\n";
 	print "<div class = 'content_game_session'>\n";
 	print "<p>Game is now playing...\n";
 	print "</p>\n";
 	$minutes = range("2", "45", 3);
 	print "Kick off!\n";
 	foreach ($minutes as $minute) 
 	{
 		$event = array("Shot from outside the box - Goal!", "Yellow Card", "Uh-Oh: 2nd Yellow equals a Red", "Red Card", "Penalty: Goal!", "Penalty: Miss!",
 		"TeamA has possession", "TeamB has possession", "Corner Kick", "Free Kick", "Header from inside the box - Goal!", "Easy tap in - Goal!", "Fine save from the Goalkeeper",
 		"Richochet off the woodwork! - Unlucky", "Easy catch for the Goalkeeper", "Beautiful throughball", "Switching the field", "Unstoppable shot into the upper 90 of the net - Goal!");
 		print '<pre>'; #<pre></pre> confuses browser to print array line by line
 		print_r($minute.'min'.' '.'-'.' '.$event[array_rand($event)]."\n");
 		print '</pre>';
 	} // end foreach loop
 	print "45min - End Half\n";
 	print "</div>";
 	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
 	print "<div class = 'button_submit'>\n";
 	print "<p><input type = 'submit' value = 'Go Home' name = 'Home'/>\n";
	print "</p>\n";
	print "</div>\n";
 	print "</form>\n";
 	print "</div>";
 	print "</div>";
 }
 ?>

<?php
 //---------------Initiate session---------------//
 session_start();
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
 	$_SESSION['Teams'] = $team;
 	$_SESSION['Teams2'] = $team2;
	$team = $_POST['Teams'];
	$team2 = $_POST['Teams2'];
	viewPlayers($team, $team2);
 } // end if
 if ($_POST['Submit_Players'])
 {
 	$_SESSION['Players'] = $team;
 	$_SESSION['Players2'] = $team2;
 	$_SESSION['Team_Name'] = $desc;
 	$_SESSION['Team_Name2'] = $desc2;
 	$team = $_POST['Players'];
 	$team2 = $_POST['Players2'];
 	$desc = $_POST['Team_Name'];
 	$desc2 = $_POST['Team_Name2'];
 	teamStats($team, $team2, $desc, $desc2);
 } // end if 
 if ($_POST['Team_Stats_Cont'])
 {
 	$_SESSION['Teams'] = $team;
 	$_SESSION['Teams2'] = $team2;
 	$_SESSION['Team_Name'] = $desc;
 	$_SESSION['Team_Name2'] = $desc2;
 	$team = $_POST['Teams'];
 	$team2 = $_POST['Teams2'];
 	$desc = $_POST['Team_Name'];
 	$desc2 = $_POST['Team_Name2'];
	playGame($team, $team2);
 }
 else if ($_POST['Home'])
 {
 	viewPage();
 } // end else if
 printDocFooter();

 //---------------------------Functions------------------------//

 /*function displayTime()
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
 */

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
 function teamStats($team, $team2, $desc, $desc2)
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Team Stats </h1>\n";
 	print "<div class = 'content_stats'>\n";
 	print "<div class = 'content_left2'>\n";
 	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
 	print "<p>AC Milan";
 	print "</p>\n";
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
 	print "\n";
 	print "<p>\n";
 	print "</p>\n";
 	print "Team Weight: ".number_format(($avgRate_D + $avgRate_A + $avgRate_G)*3 + 0, 1);
 	print "</div>\n";

 	//------------------2nd set of queries for 2nd team--------------//

 	print "<div class ='content_right2'>\n";
 	print "<p>Palermo";
 	print "</p>\n";
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
 	print "\n";
 	print "<p>\n";
 	print "</p>\n";
 	print "Team Weight: ".number_format(($avgRate_D2 + $avgRate_A2 + $avgRate_G2)*3 + 0, 1);
 	print "</div>\n";
 	print "</br>\n";
 	print "</br>\n";
 	print "</br>\n";
 	print "</br>\n";
 	print "</br>\n";
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

 function playGame($team, $team2)
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Game Session </h1>\n";
 	print "<div class = 'content_game_session'>\n";
 	print "<p><strong>Game is now playing...\n</strong>";
 	print "</p>\n";
 	#$minutes = range("2", "88", 5);
 	print "<p><strong>Kick off! </strong> <img src ='$imageFoul'>\n";
 	print "</p>\n";

 	$imageYC = 'yellowcard.jpg';
 	$imageRC = 'redcard.jpg';
 	$imagePK = 'pk.png';
 	$imagePKM = 'pkmiss.png';
 	$imageGoal = 'goal.png';
 	$imageFoul = 'foul.png';

 	$event = array("6' <br/> Corner kick for AC Milan <br/> No luck for AC Milan",
 					"9' <br/> Attempt on goal for Palermo <br/> No luck for Palermo",
 					"12' <br/> Corner kick for AC Milan <br/> No luck for AC Milan",
 					"21' <br/> Cross into AC Milan box <br/> No luck for Palermo",
 					"30' <br/> Foul called on AC Milan <br/> <img src ='$imageYC'> AC Milan",
 					"32' <br/> Attempt on goal for Palermo <br/> Goal! Palermo scores! 
 					<br/> <img src ='$imageGoal'> Palermo <br/> AC Milan 0 - 1 Palermo",
 					"36' <br/> Attempt on goal for AC Milan <br/> No luck for AC Milan",
 					"39' <br/> Attempt on goal for AC Milan <br/> No luck for AC Milan",
 					"45' <br/> Half Time",
 					"48' <br/> Foul inside the box! <br/> <img src ='$imagePK'> Penalty Kick for AC Milan 
 					<br/> Goal! AC Milan scores! <br/> <img src ='$imageGoal'> AC Milan 
 					<br/> AC Milan 1 - 1 Palermo",
 					"54' <br/> Corner kick for Palermo <br/> No luck for Palermo",
 					"55' <br/> Foul called on Palermo <br/> <img src ='$imageYC'> Palermo",
 					"66' <br/> Corner kick for Palermo <br/> Goal! Palermo scores! 
 					<br/> <img src ='$imageGoal'> Palermo <br/> AC Milan 1 - 2 Palermo",
 					"71' <br/> Cross into Palermo box <br/> Goal! AC Milan scores! 
 					<br/> <img src='$imageGoal'> AC Milan <br/> AC Milan 2 - 2 Palermo",
 					"76' <br/> Attempt on goal for AC Milan </br> No luck for AC Milan",
 					"82' <br/> Corner kick for AC Milan <br/> No Luck for AC Milan",
 					"88' <br/> Foul called on AC Milan <br/> <img src ='$imageYC'> AC Milan",
 					"90' <br/> <img src ='$imageFoul'> Full Time");
 	$arrayLength = count($event);
 	for ($x = 0; $x < $arrayLength; $x++)
 	{
 		print $event[$x];
 		print "<br/>\n";
 		print "<br/>\n";
 	}// end for loop
 	print "<p><strong>Full Time Score:</strong>\n";
 	print "</p>\n"; 
 	print "<strong>AC Milan 2 - 2 Palermo </strong>\n";
 	print "</div>";
 	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
 	print "<div class = 'button_submit'>\n";
 	print "<p><input type = 'submit' value = 'Go Home' name = 'Home'/>\n";
	print "</p>\n";
	print "</div>\n";
 	print "</form>\n";
 	print "</div>";
 	print "</div>";
 } // end playGame()
 ?>

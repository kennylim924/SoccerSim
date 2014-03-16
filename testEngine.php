<?php
 require "classfun.php";   # gets functions we will use
 require "mydb.php"; # gets function we will use to connect to database
 ## call a method to output the document's page heading
 printDocHeading("./style2.css", "Engine Testing");
 print "<body>\n";
 ## if nothing has been posted to the page (first time page is being loaded)
 if (empty($_POST)) 
 {	
 	#viewTeams();
	displayTime();
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
 	$team2 = $_POST['Players'];
 	teamStats($team);
 } // end if 
 printDocFooter();

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
	print "<div class = 'button_submit'>";
	print "<input type = 'submit' value = 'Submit' name = 'Submit_Team'/>\n";
	print "</div>\n";
	print "</form>";
	print "</div>";
	print "</div>";
 } // end viewTeams()

 function viewPlayers($team, $team2)
 {
 	print "<div class = 'content'>\n";
 	print "<h1> View Players </h1>\n";
 	print "<div class = 'content2'>\n";	
 	print "<div class = 'content_left'>\n";
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
	print "<input type = 'submit' value = 'Continue' name = 'Submit_Players'>";
	print "</div>\n";
	print "<div class = 'content_right'>\n";
	print "<p><select name = 'Players'>";
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
	print "</form>";
	print "</div>";
	print "</div>";
 } // end viewPlayers()

 function teamStats($team)
 {
 	print "<div class = 'content'>\n";
 	print "<h1> Team Stats </h1>\n";
 	print "<div class = 'content2'>\n";
 	print "<form method = 'post' enctype = 'multipart/form-data' action = '".$_SERVER ['PHP_SELF']."'>\n";
 	$db = adodbConnect();
 	// query the DB for the 'DEF' enum
 	$query = "select AVG(Rating) from Players where Team_ID = '$team' and Position = 'DEF'";
 	$result = $db -> Execute($query);
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
 	$query2 = "select AVG(Rating) from Players where Team_ID = '$team' and Position = 'ATT'";
 	$result = $db -> Execute($query2);
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
 	$query3 = "select Rating from Players where Team_ID = '$team' and Position = 'GK'";
 	$result = $db -> Execute($query3);
 	while ($row = $result -> FetchRow())
 	{
 		$avgRate_G = $row['Rating'];
 		print "Goalkeeper: ";
 		print $avgRate_G + 0;  
 	} // end while
 	print "</form>\n";
 	print "</div>";
 	print "</div>";
 } // end wtf

 ?>

<?php 
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: logout.php(admin page)
 *  Purpose: This will check whether the user is logged in or not, if he/she does logged in then it will destroys the user's session
 */
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
	<?php 
		session_start();

		/* connecting to database*/	
		$db_host = "localhost";
		$db_username ="root";
		$db_name = "suresh_database";
		@mysql_connect("$db_host","$db_username") or die ("couldn't connect to MySql");
		@mysql_select_db("$db_name") or die ("no database");

		function mss($value){
			return mysql_real_escape_string(trim(strip_tags($value)));
		}
		
		if (isset($_SESSION['username'])) {      //if the user is logged in
			session_destroy();     				 //destroys the user's session
			echo "<br><br><h3>You have successfully logged out, If you wish to login again please<a href=\"http://localhost/project\">click here</a></h3>";
			header("Location: http://localhost/project");
		}
		else {
			echo "<br><br><h3>You are already logged out, If you wish to login again please<a href=\"http://localhost/project\">click here</a></h3>";
			header("Location: http://localhost/project");
		}
	?>
	</body>
</html>
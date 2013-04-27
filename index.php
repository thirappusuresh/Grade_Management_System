<?php
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: index.php(Login page)
 *  Purpose: This file takes both the username and password as input and checks whether the record is there in database
	of their respective category(admin/student). If it exists in database then it will give access to the user.
 */
?>

<?php
	session_start();
	
	/* connecting to the database */
	$tdb_host = "localhost";
	$db_username ="root";
	$db_name = "suresh_database";
	@mysql_connect("$db_host","$db_username") or die ("couldn't connect to MySql");
	@mysql_select_db("$db_name") or die ("no database");

	function mss($value){
		return mysql_real_escape_string(trim(strip_tags($value)));
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
html {
	font: 90%/1.3 arial,sans-serif;
	padding:1em;
	background:url('bg.jpg');
}
form {
	background:#fff;
	padding:1em;
	border:1px solid #eee;
}
fieldset div {
	margin:0.3em 0;
	clear:both;
}
form {
	margin:1em;
	width:27em;
}
label {
	float:left;
	width:10em;
	text-align:right;
	margin-right:1em;
}
legend {
	color:#0b77b7;
	font-size:1.2em;
}
input {
	padding:0.15em;
	border:1px solid #ddd;
	background:#fafafa;
	font:bold 0.95em arial, sans-serif;
	-moz-border-radius:0.4em;
	-khtml-border-radius:0.4em;
}
fieldset {
	border:1px solid #ddd;
	padding:0 0.5em 0.5em;
}
.radio div {
	float:left;
	white-space:nowrap;
	clear:none;
}
#submit-go {
	margin-top:1em;
	width:69px;
	height:26px;
	text-indent:-9999px;
	overflow:hidden;
	border:0;
	background:url(submit.gif) no-repeat 0 0;
	display:block;
	cursor:pointer !important; cursor:hand;
}
h2 {
	margin:1em 0 1.5em 2em; 
	font-size:1.2em; 
	font-family:Arial,Helvetica,sans-serif;
	color:#000000; 
	font-weight:bold;
}
</style>
</head>
<body>
	<?php
		
		/* checks if the user is already logged in as admin, if it does then it will direct the user to admin pages */
		if (isset($_SESSION['username']) && isset($_SESSION['admin'])) {
			//echo "You are already logged in if you wish to log out, please <a href=\"./logout.php\">click here</a>!\n";
			header("Location: admin/index.php");
		}
		
		/* checks if the user is already logged in as student, if it does then it will direct the user to student pages */
		else if (isset($_SESSION['loginuser']) && isset($_SESSION['student'])) {
			//echo "You are already logged in if you wish to log out, please <a href=\"./logout.php\">click here</a>!\n";
			header("Location: students/index.php");
		}
		
		/* It will take the inputs username and password then checks record in database, if it does then it gives access*/
		else {
			$login=(isset($_GET['login']));
			if($login=="yes"){    						//checking whether the form is submitted or not.
				$selected_radio = $_POST['choice'];		//getting the category(admin/student)
				$user = mss($_POST['username']);		//getting the username
				$pass = $_POST['password'];				//getting the password
                if($user && $pass) {					
					
					if ($selected_radio == 'admin') {	//if the category is admin it will enter into this case
						$sql = "SELECT username FROM `users` WHERE `username`='".$user."'";
						$res = mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($res) > 0) {
							$sql2 = "SELECT username FROM `users` WHERE `username`='".$user."' AND `password`='".$pass."'";
							$res2 = mysql_query($sql2) or die(mysql_error());
							if(mysql_num_rows($res2) > 0) {
								$row = mysql_fetch_assoc($res2);
								$_SESSION['username'] = $user;
								$_SESSION['admin'] = $selected_radio;
								echo "You have succsessfully logged in as " . $_SESSION['username'];
								header("Location: admin/index.php");
							} 
							else { 
								$message = "Username and/or password are not valid in ".$selected_radio;
							}
						} 
						else {
							$message = "The username you supplied does not exist in ".$selected_radio;
						}
					}
					else if ($selected_radio == 'student') {	//if the category is student it will enter into this case
						$sql = "SELECT username FROM `students` WHERE `username`='".$user."'";
						$res = mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($res) > 0) {
							$sql2 = "SELECT username FROM `students` WHERE `username`='".$user."' AND `password`='".$pass."'";
							$res2 = mysql_query($sql2) or die(mysql_error());
							if(mysql_num_rows($res2) > 0) {
								$row = mysql_fetch_assoc($res2);
								$_SESSION['loginuser'] = $row['username'];
								$_SESSION['student'] = $selected_radio;
								echo "You have succsessfully logged in as " . $_SESSION['username'];
								header("Location: students/index.php");
							} 
							else { 
								$message = "Username and/or password are not valid in ".$selected_radio;
							}
						} 
						else {
							$message = "The username you supplied does not exist in ".$selected_radio;
						}
					}
					else {
						$message = "Please check proper radio button";
					}
				}
				else {
					$message = "Please fill the username and password fields in ".$selected_radio;
				}
			}
			?>	
			<p align=center>
			<?php	
			if ( !empty($message) ) {
				echo '<span style="color:red">',$message,'</span><br>';
			}
			
			/* Form to get the inputs */
			?>
			</p>
			<h2 align=center>
			<form method=post action='index.php?login=yes'>
			<fieldset>
				<legend>Login Details</legend>
				<div>
					<label for="username">Username</label> <input type=text id='username' name='username'>
				</div>
				<div>
					<label for="password">Password</label> <input type=password id='password' name='password'>
				</div>
				<table>
				<tr>
				<td><input type="radio" name="choice" value='admin' checked/>  admin</td>
				<td><input type="radio" name="choice" value='student'/>  student</td>
				</tr>
				</table>
			</fieldset>
			<div><button type=submit name='submit' id="submit-go"  value='Login' ></button></div>
			</form>
			</h2>
			<?php
		} //end of else case
	?>
</body>
</html>
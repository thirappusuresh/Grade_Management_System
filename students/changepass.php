<?php 
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: changepass.php(student page)
 *  Purpose: This will allow the student to change his/her password.
 */
?>

<?php
	session_start();
	$error = false;
	if ($_SESSION['loginuser']) { 
		$_SESSION['loginuser'];
		$id = $_SESSION['loginuser'];
	if (!empty($id)) {
		$_SESSION['recordId']=$id; 
	}
    else { 
	$id = $_SESSION['recordId']; 
	}
	
	// the script has been called if it has empty login ID or if user hit "Cancel" it just return to index.php(student page)
	if (empty($id) || isset($_POST['cancel'])) { 
		Header("Location: index.php"); 
		exit; 
	}
	
	// runs this only if the user has hit the "OK" button
	if (isset($_POST['ok'])) {
		// assign form inputs
		$pass = $_POST['password'];
		$new_password = $_POST['new_password'];
		$confirm_new_password = $_POST['confirm_new_password'];
		$link=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
		mysql_select_db('suresh_database') or die ("no database");
		$query = "SELECT password FROM students WHERE username='".$id."'";
		$result = mysql_query($query) or die(mysql_error());
		$grade = mysql_fetch_assoc($result);
		mysql_close($link);
		$password = $grade['password'];
		if($password != $pass) {
			$message = "You entered an incorrect Old_Password";
		}
		else if (empty($new_password)) {
			$message1 = "Please enter your New_Password";
		}
		else if ($new_password != $confirm_new_password) {
			$message2 = "The New_Password and Confirm_New_Password fields must be the same";
		}
		// validate inputs
		else {    
			// add member to database
			$con=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
			mysql_select_db('suresh_database') or die ("no database");
			$query = "UPDATE students SET password='".$new_password."' WHERE username='".$id."'";
			$result = mysql_query($query) or die(mysql_error());
			mysql_close($con);      
			$message3 = "You have successfully changed your password";
			//Header("Location: index.php"); 
			//exit; 
		}
	};
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<script language="JavaScript">
			<!--
			
			/* this function is to get the confirmation from the user to logout*/
			function confirmLogout(){
			var agree = confirm("Are you wish to logout")
			if(agree)
				return true
			else
				return false
			}
		</script>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style>
			body {
				background:url('bg1.jpg');
				color: white; 
				margin:0; 
				padding:0; 
				text-align:center;
			}
			#container {
				background-color:none;
				width:1100px; 
				margin: 0 auto;
				text-align:left
			}
			table.first {
				background:url('bg.jpg');
				border:solid 0px #F1F1F1;
				color:#FFFFFF;
			}
			h3 {
				align:left;
				margin:1em 0 1.5em 2em; 
				font-size:1.2em; 
				font-family:Arial,Helvetica,sans-serif;
				color:#FFFFFF; 
				font-weight:bold;
			}
			form {
				align:center;
				width:350px; 
				margin:0 auto 0 auto;
			}
			fieldset.first{
				background:none;
			}
		</style>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<title>Change_Password</title>
	</head>
	<body>
	<?php 	
	echo "<p align=right >Welcome back, ".$_SESSION['loginuser'];
	echo "! <a href=\"./changepass.php\" >Change_Password</a>";
	echo " | <a href=\"./logout.php\" onClick=\"return confirmLogout()\">Logout</a></p>";
	?>
	<div id="container">
		<table class=first border="0" width=1090px style="table-layout:fixed">
		<col width=186>
		<col width=1>
		<col width=901>
		<tr align=center>
			<td align=center valign=top>
				<div align=center style="overflow:auto;height:480px">
					<h3><a href="index.php">Grades</a></h3>
				</div>
			</td>
			<td style="border-left: dashed;"></td>
			<td>
			<div align=center style="overflow:auto;height:480px">
			<form name="form1" method=POST action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset class="first">
			<h3 align=center>Change_Password</h3>
			<?php
		    if ( !empty($message) ) {
				echo '<span style="color:green">',$message,'</span><br>';
			}
			?>
			<b>Old_Password:</b>
				<input name="password" type="text"  >
			<br>
			<br>
			<?php
			if ( !empty($message1) ) {
				echo '<span style="color:green">',$message1,'</span><br>';
			}
			?>
			<b>New_Password:</b>
			<input name="new_password" type="text" >
			<br>
			<br>
			<?php
			if ( !empty($message2) ) {
				echo '<span style="color:green">',$message2,'</span><br>';
			}
			?>
			<b>Confirm_New_Password:</b>
			<input name="confirm_new_password" type="text">
			</fieldset>
			<input class"btn" type="hidden" name="id" value="<?php echo $id; ?>">
			<input class"btn" type="submit" name="ok" value="    OK    ">
			<input class"btn" type="submit" name="cancel" value="Cancel">
			</form>
			<?php
			if ( !empty($message3) ) {
				echo '<br><h3 style="color:green">',$message3,'</h3>';
			}
	}
	else {
		header("Location: http://localhost/project");
	}
	?>
</div>
</td>
</tr>
</table>
</body>
</html>
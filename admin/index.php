<?php 
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: index.php(admin page)
 *  Purpose: This file takes the input as any of the combinations displayed on the page and runs the sql query for that
 *  combination then displays the list of grades.
 */
?>

<?php 
	session_start(); 
	$error = false;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<title>Search</title>
		<script language="Javascript">
		
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
				color: white; margin:0; 
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
			fieldset.first {
				background:none;
			}
		</style>
	</head>
<body style="overflow:auto;">
<?php 	
	echo "<p align=right >Welcome back, ".$_SESSION['username'];
	echo "! <a href=\"./changepass.php\" >Change_Password</a>";
	echo " | <a href=\"./logout.php\" onClick=\"return confirmLogout()\">Logout</a></p>";
?>
<?php 
	if($_SESSION['username']) {    //it will enter into this case iff the user is logged in.
		$conn=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
		mysql_select_db('suresh_database') or die ("no database");
		$sql = "SELECT username From `users` WHERE `username`='".$_SESSION['username']."'";
		$res = mysql_query($sql) or die(mysql_error());
		mysql_close($conn);
		
		//if the user not there in database this will destroys his/her session
		if(mysql_num_rows($res) == 0) {
			session_destroy();     
			//echo "please <a href=\"http://localhost/project\">login</a>";
			Header("Location: http://localhost/project");
		}
		
		/*if the user is logged in and the user record is there in database then it enters into this case*/
		else {	
			?>
			<div id="container">
			<table class=first border="0" width=1090px style="table-layout:fixed">
			<col width=186>
			<col width=1>
			<col width=400>
			<col width=501>
			<tr align=center>
			<td align=center valign=top>
			<div align=center style="overflow:auto;height:480px">
			<h3 >Search</h3>
			<h3><a href="addgrade.php">Add a Grade</a></h3>
			<h3 ><a href="upload.php">Upload</a></h3>
			</div>
			</td>
			<td style="border-left: dashed;"></td>
			<td align=center valign=top>
				<p align=center><span style="color:green">You'r requested to submit any of the following combinations</span></p>
				<p align=left>1) Roll_No</p>
				<p align=left>2) Roll_No & Course</p>
				<p align=left>3) Roll_No & Semester</p>
				<p align=left>4) Batch & Course</p>
				<p align=left>5) Batch & Course & Semester</p>
			</td>
			<td>
			<div align=left style="height:480px">
			
			<?php
			//form to submit any of the combinations
			?>
			<form action='insidesearch.php' method=POST>
			<fieldset class="first">
			<br>
			<b>Roll_No:</b>
			<input name="roll_no" type="text" >
			<br>
			<br>
			<b>Course:</b>
			<input name="course" type="text" >
			<br>
			<br>
			<b>Batch:</b>
			<select name="batch">
			<option value="" <?php if (empty($batch)) echo "selected";?>>- select -</option>
				<?php
				$batch = $_POST['batch'];
				for ($year=2008; $year<2031; $year++) {
					echo " <option";
					if ($batch==$year) { 
						echo " selected"; 
					}
					echo ">",$year,"</option>\n";
				}
				?>
			</select>
			<br>
			<br>
			<b>Semester:</b>
			<select name="sem">
			<option value="" <?php if (empty($sem)) echo "selected";?>>- select -</option>
				<?php
				$sem = $_POST['sem'];
				for ($seme=1; $seme<9; $seme++) {
					echo " <option";
					if ($sem=="semester_$seme") { 
						echo " selected "; 
					}
					echo ">","semester_$seme","</option>\n";
				}
				?>
			</select>
			<br>
			</fieldset >
			<fieldset >
			<input class="btn" type="submit" name="submit1" value="submit">
			</fieldset >
			</form>
			</div>
			</td>
			</tr>
			</table>
			<?php
		}  //end of else case
	}   //end of if case which is user session
	
	/*if the user not logged in then this will direct him to login page without giving access to this page*/
	else {
		//echo "Please <a href=\"http://localhost/project\">login</a>";
		header("Location: http://localhost/project");  //directing to login page
	}		
?>
</body>
</html>
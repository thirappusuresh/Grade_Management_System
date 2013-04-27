<?php 
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: addgrade.php(admin page)
 *  Purpose: This will allow the administrator to add grades individually one by one.
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
		<title>Adding Grades</title>
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
	if($_SESSION['username']) {      //it will enter into this case iff the user is logged in.
		$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
		mysql_select_db('suresh_database') or die ("no database");
		$sql = "SELECT username From `users` WHERE `username`='".$_SESSION['username']."'";
		$res = mysql_query($sql) or die(mysql_error());

		//if the user not there in database this will destroys his/her session	
		if(mysql_num_rows($res) == 0) {
			session_destroy();
			echo "please <a href=\"http://localhost/project\">login</a>";
		}
		
		/*if the user is logged in and the user record is there in database then it enters into this case*/
		else {
			
			// runs this only, once the user has hit the "submit" button
			if (isset($_POST['submit'])) {   
				
				// assigning form inputs
				$sroll_no = $_POST['roll_no'];
				$scourse = $_POST['course'];
				$sname = $_POST['name'];
				$sgrade = $_POST['grade'];
				$sem = $_POST['sem'];
				$batch = substr($sroll_no, 2, -4);
				$check = "20".$batch."";
				$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
				if (!mysql_select_db($check)) { $message4 = "You must enter a Roll_No";}
				$query = "SELECT roll_no,course FROM semester_1 WHERE roll_no='".$sroll_no."' AND course='".$scourse."' UNION ALL SELECT roll_no,course FROM semester_2 WHERE roll_no='".$sroll_no."' AND course='".$scourse."' UNION ALL SELECT roll_no,course FROM semester_3 WHERE roll_no='".$sroll_no."' AND course='".$scourse."' UNION ALL SELECT roll_no,course FROM semester_4 WHERE roll_no='".$sroll_no."' AND course='".$scourse."' UNION ALL SELECT roll_no,course FROM semester_5 WHERE roll_no='".$sroll_no."' AND course='".$scourse."' UNION ALL SELECT roll_no,course FROM semester_6 WHERE roll_no='".$sroll_no."' AND course='".$scourse."' UNION ALL SELECT roll_no,course FROM semester_7 WHERE roll_no='".$sroll_no."' AND course='".$scourse."' UNION ALL SELECT roll_no,course FROM semester_8 WHERE roll_no='".$sroll_no."' AND course='".$scourse."'";
				$result = mysql_query($query);
				if (!mysql_query($query)) {
						$message3 = "You must select a Semester";
				}
				else if(mysql_num_rows($result) > 0) {
					$message1 = "The pair (roll_no,course) is already there in database";
				}			
		        else if ( !empty($sroll_no) && !empty($sname) && !empty($scourse) && !empty($sgrade) && !empty($sem) ) {    
					// add member to database
					$query = "INSERT INTO ".$sem." (roll_no,name,course,grade) VALUES ('".$sroll_no."','".$sname."','".$scourse."','".$sgrade."')";
					if (!mysql_query($query)) {
						$message2 = "You must select a Semester";
					}
					$message = "Course = '".$scourse."' Grade has been successfully added to Roll_No = '".$sroll_no."'";
					//Header("Location: listgrades.php"); 
                    //exit; 
				}
				else {
					$error = true; // input validation failed
				}
				mysql_close($batch_check);
			}
			?>
			<div id="container">
			<table class=first border="0" width=1090px style="table-layout:fixed">
			<col width=186>
			<col width=1>
			<col width=901>
			<tr align=center>
			<td align=center valign=top>
			<div align=center style="overflow:auto;height:480px">
			<h3 ><a href="index.php">Search</a></h3>
			<h3 >Add a Grade</h3>
			<h3 ><a href="upload.php">Upload</a></h3>
			</div>
			</td>
			<td style="border-left: dashed;"></td>
			<td>
			<div align=center style="overflow:auto;height:480px">
			<form action='addgrade.php?act=submission_of_grades' method=POST>
			<fieldset class="first">
			<?php
			if ( !empty($message) ) {
				echo '<span style="color:green">',$message,'</span><br>';
			}
			if ( !empty($message1) ) {
				echo '<span style="color:green">',$message1,'</span><br>';
			}
			if ( !empty($message2) ) {
				echo '<span style="color:green">',$message2,'</span><br>';
			}
			if ( !empty($message3) ) {
				echo '<span style="color:green">',$message3,'</span><br>';
			}
			if ( !empty($message4) ) {
				echo '<span style="color:green">',$message4,'</span><br>';
			}
			?>
			<br>
			<?php
			if ( $error && empty($roll_no) ) {
				echo '<span style="color:red">Error! Please enter a Roll_No.</span><br>',"\n";
			}
			?>
			<b>Roll_No:</b>
			<input name="roll_no" type="text" >
			<br>
			<br>
			<?php
			if ( $error && empty($course) ) {
				echo '<span style="color:red">Error! Please enter a Course.</span><br>',"\n";
			}
			?>
			<b>Course:</b>
			<input name="course" type="text" >
			<br>
			<br>
			<?php
			if ( $error && empty($name) ) {
				echo '<span style="color:red">Error! Please enter a Student_Name.</span><br>',"\n";
			}
			?>
			<b>Student_Name:</b>
			<input name="name" type="text" >
			<br>
			<br>
			<?php
			if ( $error && empty($grade) ) {
				echo '<span style="color:red">Error! Please enter a Grade.</span><br>',"\n";
			}
			?>
			<b>Grade:</b>
			<input name="grade" type="text" >
			<br>
			<br>
			<?php
			if ( $error && empty($sem) ) {
				echo '<span style="color:red">Error! Please select a Semester.</span><br>',"\n";
			}
			?>
			<b>Semester:</b>
			<select name="sem">
			<option value="0" <?php if (empty($sem)) echo "not selected";?>>- select -</option>
				<?php
				$sem = $_POST['sem'];
				for ($seme=1; $seme<9; $seme++) {
					echo " <option";
					if ($sem=="semester_$seme") { 
						echo " selected"; 
					}
					echo ">","semester_$seme","</option>\n";
				}
				?>
			</select>
			<br>
			</fieldset >
			<fieldset >
			<input class="btn" type="submit" name="submit" value="submit">
			</fieldset >
			</form>
			</div>
			</td>
			</tr>
			</table>
			<?php			
		}
	}
	else {
		echo "Please <a href=\"http://localhost/project\">login</a>";
		header("Location: http://localhost/project");
	}		
?>
</body>
</html>
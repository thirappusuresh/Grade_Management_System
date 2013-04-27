<?php 
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: upload.php(admin page)
 *  Purpose: This will allow the administrator to upload grades into database.
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
	if($_SESSION['username']) {      //it will enter into this case iff the user is logged in.
		$conn=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
		mysql_select_db('suresh_database') or die ("no database");
		$sql = "SELECT username From `users` WHERE `username`='".$_SESSION['username']."'";
		$res = mysql_query($sql) or die(mysql_error());
		mysql_close($conn);
		
		//if the user not there in database this will destroys his/her session
		if(mysql_num_rows($res) == 0) {
			session_destroy();
			echo "please <a href=\"http://localhost/project\">login</a>";
			Header("Location: http://localhost/project");
		}
		
		/*if the user is logged in and the user record is there in database then it enters into this case*/
		else {	 
			if(isset($_POST['submit'])) {   //if submits the grades
				if ($_POST['course'] && $_POST['sem']) {
				
				//assigning inputs from the uload form
				$sem = $_POST['sem'];
				$course = $_POST['course'];
				$batch = $_POST['batch'];
				
				//opening the uploaded (*.csv file)
				$fd = fopen ("C:/csv/". $_FILES["file"]["name"], "r");
				if ( $fd !== FALSE ) {     //if .csv file is not emty	
					$count = 0;
					$cnt = 0;
					
					/*reading the file line by line till all the lines are read and each line is stores the 
					 * data in data[0] till it found ',' ;till it found next ',' it stores the data 
					 * in data[1] ... till all ',' are found
					 */
					while (($data = fgetcsv($fd, 1000, ",")) !== FALSE) {		
						if($data[0] != NULL && $data[1] != NULL && $data[2] != NULL){   //if the data before first three comma's are stored in data[] and they are not null
							$count++;
							
							/*to skip the first line with record*/
							if ($count == 1) { continue; }
							$conn=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
							if (!mysql_select_db($batch)) { 
								$message4 = "You must select both Batch and Semester";
								break;
							}
							$query = "SELECT roll_no,course FROM semester_1 WHERE roll_no='".$data[0]."' AND course='".$course."' UNION ALL SELECT roll_no,course FROM semester_2 WHERE roll_no='".$data[0]."' AND course='".$course."' UNION ALL SELECT roll_no,course FROM semester_3 WHERE roll_no='".$data[0]."' AND course='".$course."' UNION ALL SELECT roll_no,course FROM semester_4 WHERE roll_no='".$data[0]."' AND course='".$course."' UNION ALL SELECT roll_no,course FROM semester_5 WHERE roll_no='".$data[0]."' AND course='".$course."' UNION ALL SELECT roll_no,course FROM semester_6 WHERE roll_no='".$data[0]."' AND course='".$course."' UNION ALL SELECT roll_no,course FROM semester_7 WHERE roll_no='".$data[0]."' AND course='".$course."' UNION ALL SELECT roll_no,course FROM semester_8 WHERE roll_no='".$data[0]."' AND course='".$course."'";
							$result = mysql_query($query);
							if(mysql_num_rows($result) > 0) {
								$message2 = "Some of the records (roll_no,course) are already there in database";
							}
							else {
								$cnt++;
								$sql = "INSERT into ".$sem."(roll_no,course,name,grade) values('$data[0]','$course','$data[1]','$data[2]')";
								mysql_query($sql) or die(mysql_error());
								mysql_close($conn);
								$message = "Successfully imported '".$cnt."' rows except the first row which is header";	
							}	
						}
					}
					fclose($fd);	//closing the opened file	
				}
				else {
					$message1 = "Invalid File";
				}   	
				}
				else {
					$message5 = "Please enter a course and select a semester";
				}
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
			<h3><a href="addgrade.php">Add a Grade</a></h3>
			<h3 >Upload</h3>
			</div>
			</td>
			<td style="border-left: dashed;"></td>
			<td>
			<div align=center style="overflow:auto;height:480px">
			
			<?php		
			/*form to submit the grades list*/
			?>
			<form action='upload.php' method=POST enctype="multipart/form-data">
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
			if ( !empty($message4) ) {
				echo '<span style="color:green">',$message4,'</span><br>';
			}
			if ( !empty($message5) ) {
				echo '<span style="color:green">',$message5,'</span><br>';
			}
			?>
			<br>
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			<b>Import_File:</b>
			<input name="file" type="file" size='20'>
			<br>
			<br>
			<b>Course:</b>
			<input name="course" type="text" >
			<br>
			<br>
			<b>Batch:</b>
			<select name="batch">
			<option value="0" <?php if (empty($batch)) echo "selected";?>>- select -</option>
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
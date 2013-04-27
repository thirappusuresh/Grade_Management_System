<?php 
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: insidesearch.php(admin page)
 *  Purpose: This will get activated to display the list of grades iff the user submit any of the combination in 
 *	index.php(admin page).
 */
?>

<?php
	/* After submission is done these are the cookies that set to browser and will get expires after 360000 seconds.
	 * We need these later on to show the list of grades untill the new submission is done by the user then 
	 * these cookies get reset according to the new submission.	
	 */
	if (isset($_POST['submit1'])) {
	setcookie("rollno", $_POST['roll_no'], time()+360000, "/","", 0);
	setcookie("ccourse", $_POST['course'], time()+360000, "/","", 0);
	setcookie("cbatch", $_POST['batch'], time()+360000, "/","", 0);
	setcookie("csem", $_POST['sem'], time()+360000, "/","", 0);
	}
	
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
			
			/* this function is to get the confirmation from the user to delete a grade from list/database*/
			function confirmDelete(delUrl) {
				if (confirm("Are you sure you want to delete")) {
					document.location = delUrl;
				}
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
	if($_SESSION['username']) {     //it will enter into this case iff the user is logged in.
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
			<col width=901>
			<tr align=center>
			
			<td align=center valign=top>
			<div align=center style="overflow:auto;height:480px">
			<h3 ><a href="index.php">Search</a></h3>
			<h3><a href="addgrade.php">Add a Grade</a></h3>
			<h3 ><a href="upload.php">Upload</a></h3>
			</div>
			</td>
			
			<td style="border-left: dashed;"></td>
			
			<td>
			<?php
			
			/*This funetion is called inside function edit() */ 
			function in_edit($query) {
				?>
				<div align=center style="overflow:auto;height:480px">
				<table border="1" cellspacing="0" cellpadding="6">
					<tr bgcolor="#000000">
						<th><strong>Roll_No</strong></th>
						<th><strong>Course</strong></th>
						<th><strong>Student_Name</strong></th>
						<th><strong>Grade</strong></th>
						<th><strong>Semester</strong></th>
						<th>&nbsp;</th>
					</tr>
				<?php
				$result = mysql_query($query) or die(mysql_error());
				for ($i=0; $i<mysql_num_rows($result); $i++) {
					$grade = mysql_fetch_assoc($result);
					?>
					<tr>
						<td><?php echo $grade['roll_no']; ?></td>
						<td><?php echo $grade['course']; ?></td>
						<td><?php echo $grade['name']; ?></td>
						<td><?php echo $grade['grade']; ?></td>
						<td><?php echo "semester_".$_POST['semi']; ?></td>
						<td>
							<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
							<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
						</td>
					</tr>
					<?php	
				}
				?>
				</table>
				</div>
			<?php
			}//end of in_edit();
	
			/* This function is called when the user click the ok/cancel in edit form to show the modified/previous data
			 * respectively	and this is where we use cookies to show the previous list with modified/same data 
			 * which is modified by the user request in the edit form
			 */
			function edit() {
				if( isset($_COOKIE["rollno"])) {    //if roll_no is set
					$eroll_no = $_COOKIE["rollno"];
					$batch = substr($eroll_no, 2, -4);
					$check = "20".$batch."";
					$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
					mysql_select_db($check) or die ("nooooo database");
					if (isset($_COOKIE["csem"])) {   //roll_no and semester combination
						$ssem = $_COOKIE["csem"];
						$ssemester = "$ssem";
						$query = "SELECT * FROM ".$ssemester." WHERE roll_no='".$eroll_no."'";
						in_edit($query);
					} 
					else if (isset($_COOKIE["ccourse"])) {   //roll_no and course combination
						$scourse = $_COOKIE["ccourse"];
						?>
						<div align=center style="overflow:auto;height:480px">
						<table border="1" cellspacing="0" cellpadding="6">
						<tr bgcolor="#000000">
							<th><strong>Roll_No</strong></th>
							<th><strong>Course</strong></th>
							<th><strong>Student_Name</strong></th>
							<th><strong>Grade</strong></th>
							<th><strong>Semester</strong></th>
							<th>&nbsp;</th>
						</tr>
						<?php
						for ($seme=1; $seme<9; $seme++) {
							$sem = "semester_".$seme."";
							$query = "SELECT * FROM ".$sem." WHERE roll_no='".$eroll_no."' AND course='".$scourse."'";
							$result = mysql_query($query) or die(mysql_error());
							for ($i=0; $i<mysql_num_rows($result); $i++) {
								$grade = mysql_fetch_assoc($result);
								?>
								<tr>
									<td><?php echo $grade['roll_no']; ?></td>
									<td><?php echo $grade['course']; ?></td>
									<td><?php echo $grade['name']; ?></td>
									<td><?php echo $grade['grade']; ?></td>
									<td><?php echo $sem; ?></td>
									<td>
										<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
										<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
									</td>
								</tr>
								<?php	
							}
						}
						?>
						</table>
						</div>
						<?php
					}
					else {       //roll_no combination
						?>
						<div align=center style="overflow:auto;height:480px">
						<table border="1" cellspacing="0" cellpadding="6">
							<tr bgcolor="#000000">
								<th><strong>Roll_No</strong></th>
								<th><strong>Course</strong></th>
								<th><strong>Student_Name</strong></th>
								<th><strong>Grade</strong></th>
								<th><strong>Semester</strong></th>
								<th>&nbsp;</th>
							</tr>
							<?php
							for ($seme=1; $seme<9; $seme++) {
								$sem = "semester_".$seme."";
								$query = "SELECT * FROM ".$sem." WHERE roll_no='".$eroll_no."'";
								$result = mysql_query($query) or die(mysql_error());
								for ($i=0; $i<mysql_num_rows($result); $i++) {
									$grade = mysql_fetch_assoc($result);
									?>
									<tr>
										<td><?php echo $grade['roll_no']; ?></td>
										<td><?php echo $grade['course']; ?></td>
										<td><?php echo $grade['name']; ?></td>
										<td><?php echo $grade['grade']; ?></td>
										<td><?php echo $sem; ?></td>
										<td>
											<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
											<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
										</td>
									</tr>
									<?php	
								}
							}
							?>
						</table>
						</div>
						<?php
					}//end of else
				}//end of if case(roll_no)
		
				else if (isset($_COOKIE["cbatch"])) {      //if batch is set
					$sbatch = $_COOKIE["cbatch"];
					$bat=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
					mysql_select_db($sbatch) or die ("no database with this batch");
					if ( isset($_COOKIE["ccourse"]) ) {    //batch and course are set
						$scourse = $_COOKIE["ccourse"];
						if ( isset($_COOKIE["csem"]) ) {   //batch, course and semester combination 
 							$ssemester = $_COOKIE["csem"];
							$query = "SELECT * FROM ".$ssemester." WHERE course='".$scourse."'";				
							in_edit($query);
						}
						else {
							?>
							<div align=center style="overflow:auto;height:480px">
							<table border="1" cellspacing="0" cellpadding="6">
							<tr bgcolor="#000000">
								<th><strong>Roll_No</strong></th>
								<th><strong>Course</strong></th>
								<th><strong>Student_Name</strong></th>
								<th><strong>Grade</strong></th>
								<th><strong>Semester</strong></th>
								<th>&nbsp;</th>
							</tr>
							<?php
							for ($seme=1; $seme<9; $seme++) {
								$sem = "semester_".$seme."";
								$query = "SELECT * FROM ".$sem." WHERE course='".$scourse."'";
								$result = mysql_query($query) or die(mysql_error());
								for ($i=0; $i<mysql_num_rows($result); $i++) {
									$grade = mysql_fetch_assoc($result);
									?>
									<tr>
										<td><?php echo $grade['roll_no']; ?></td>
										<td><?php echo $grade['course']; ?></td>
										<td><?php echo $grade['name']; ?></td>
										<td><?php echo $grade['grade']; ?></td>
										<td><?php echo $sem; ?></td>
										<td>
											<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
											<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
										</td>
									</tr>
									<?php	
								}
							}
							?>
							</table>
							</div>
							<?php
						}
					}
					else {
						header("Location: index.php");
					}
					mysql_close($bat);
				}////end of else if case(batch)
				
				else {
					header("Location: index.php");
				}
			}//end of edit() function
			
			/*if the user click on the edit option then it will enter into this case*/
			if(!empty($_GET['recordid']) && !empty($_GET['course'])) {
				$roll_no1 = $_GET['recordid'];
				$course1 = $_GET['course'];
				if (!empty($course1)) { 
					$_SESSION['recordId']=$course1; 
				}
				else { 
					$course1 = $_SESSION['recordId']; 
				}
				
				// if the script has been called without roll_no and course then the edit session will exit
				if ((empty($roll_no1) && empty($course1))) { 
					edit();
					exit; 
				}
				
				$message = "You can only modify the Student_name and Grade not the semester of record '(".$roll_no1.", ".$course1.")'";
				
				// reading required data from database of record(roll_no,course)
				$batch = substr($roll_no1, 2, -4);
				$check = "20".$batch."";
				$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
				mysql_select_db($check) or die ("no database");
				for ($seme=1; $seme<9; $seme++) {
					$sem = "semester_$seme";
					$query = "SELECT * FROM ".$sem." WHERE roll_no='".$roll_no1."' AND course='".$course1."'";
					$result = mysql_query($query) or die(mysql_error());
					if(mysql_num_rows($result) > 0) {
						$selsem = $seme;
						$grade = mysql_fetch_assoc($result);
						$roll_no = $grade['roll_no'];
						$course = $grade['course'];
						$name = $grade['name'];
						$grade = $grade['grade'];
					}
				}
				mysql_close($batch_check);
				?>
				<?php
				/*This form is for requesting the database to edit the grade */
				?>
				<div align=center style="overflow:auto;height:480px">
				<form name="form1" method=POST action="<?php echo $_SERVER['PHP_SELF']; ?>" >
				<fieldset class="first">
				<?php	
				if ( !empty($message) ) {
					echo '<b valign=top><span style="color:green">',$message,'</span></b><br>';
				}
				if ( $error && empty($roll_no) ) {
					echo '<span style="color:red">Error! Please enter a Roll_No.</span><br>',"\n";
				}
				?>
				<h3 align=center>Editing Grade</h3>
				<input name="roll_no" type="hidden" value="<?php echo $roll_no; ?>" >
				<?php
				if ( $error && empty($course) ) {
					echo '<span style="color:red">Error! Please enter a Course.</span><br>',"\n";
				}
				?>
				<input name="course" type="hidden" value="<?php echo $course; ?>">
				<?php
				if ( $error && empty($name) ) {
					echo '<span style="color:red">Error! Please enter a Student_Name.</span><br>',"\n";
				}
				?>
				<b>Student_Name:</b>
				<input name="name" type="text" value="<?php echo $name; ?>">
				<br>
				<br>
				<?php
				if ( $error && empty($grade) ) {
					echo '<span style="color:red">Error! Please enter a Grade.</span><br>',"\n";
				}
				
				?>
				<b>Grade:</b>
				<input name="grade" type="text" value="<?php echo $grade; ?>">
				<br>
				<br>
				<?php
				if ( $error && empty($sem) ) {
					echo '<span style="color:red">Error! Please select a Semester.</span><br>',"\n";
				}
				$mysem = $selsem;
				$sem[0]="1";
				$sem[1]="2";
				$sem[2]="3";
				$sem[3]="4";
				$sem[4]="5";
				$sem[5]="6";
				$sem[6]="7";
				$sem[7]="8";
				?>
				<b>Semester:</b>
				<select name="semi">
				<?php
				for ($i=0; $i<=7; $i++)
				{
					if($mysem == $sem[$i]){
						echo "<option selected value='$sem[$i]'>semester_$sem[$i]</option>";
					}
				}
				?>
				</select>
				<br>
				</fieldset>
				<fieldset>
					<input class"btn" type="submit" name="ok" value="    OK    ">
					<input class"btn" type="submit" name="cancel" value="Cancel">
				</fieldset>
				</form>
				</div>
				<?php
			}  //end of edit case

			/* if the user click the 'cancel' in edit form then it calls edit function which shows the 
			 * previous grades list without any modification
			 */
			if (isset($_POST['cancel'])) {
				edit();
			}
			
			/* if the user click the 'ok' in edit form then it calls edit function which shows the 
			 * previous grades list with modification. The modification is done according to what 
			 * the user is requested in the edit form
			 */
			if (isset($_POST['ok'])) {
				// getting the inputs from the edit form in other words getting the request of user to modify the data
				$roll_no = $_POST['roll_no'];
				$course = $_POST['course'];
				$name = $_POST['name'];
				$grade = $_POST['grade'];
				$semester = $_POST['semi'];
				$semes = "semester_$semester";
				// Making sure all the inputs are not emty
				if ( !empty($roll_no) && !empty($name) && !empty($course) && !empty($grade) ) {    
					// updating the database according to user request
					$batch = substr($roll_no, 2, -4);
					$check = "20$batch";
					$con=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
					mysql_select_db($check) or die ("no database");
					$query1 = "UPDATE ".$semes." SET roll_no='".$roll_no."',name='".$name."',course='".$course."',grade='".$grade."' WHERE roll_no='".$roll_no."' AND course='".$course."'";
					$result = mysql_query($query1) or die(mysql_error());
					$query2 = "SELECT * FROM ".$semes." WHERE roll_no='".$roll_no."' AND course='".$course."'";
					$result2 = mysql_query($query2) or die(mysql_error());
					mysql_close($con);
					if(mysql_num_rows($result2) > 0) {
						$message3 = "You have successfully updated the record '(".$roll_no.", ".$course.")'"; 
						edit();
					}
					else {
						$message4 = "Please don't change the semester options";
					}
				}
				else {
					$error = true; // if some of the inputs are empty
				}	
			}//end of 'ok'

			/*if the user click on the delete option then it will enter into this case*/
			if(!empty($_GET['roll_no']) && !empty($_GET['course'])) {
				
				// if the script has been called without roll_no and course then the delete session will exit
				if (empty($_GET['roll_no']) && empty($_GET['course'])) { 
					edit(); 
					exit; 
				} 
				$batch = substr($_GET['roll_no'], 2, -4);
				$check = "20".$batch."";
				$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
				mysql_select_db($check) or die ("connection failed");
				for ($seme=1; $seme<9; $seme++) {
					$sem = "semester_".$seme."";
					$query = "DELETE FROM ".$sem." WHERE roll_no='".$_GET['roll_no']."' AND course='".$_GET['course']."'";
					$result = mysql_query($query) or die(mysql_error());
				}
				mysql_close($batch_check);
				edit();
			}

			/*This funetion is called inside function search_result() */ 
			function inside_function($query) {
			?>
			<div align=center style="overflow:auto;height:480px">
				<table border="1" cellspacing="0" cellpadding="6">
					<tr bgcolor="#000000">
						<th><strong>Roll_No</strong></th>
						<th><strong>Course</strong></th>
						<th><strong>Student_Name</strong></th>
						<th><strong>Grade</strong></th>
						<th><strong>Semester</strong></th>
						<th>&nbsp;</th>
					</tr>
					<?php
					$result = mysql_query($query) or die(mysql_error());
					for ($i=0; $i<mysql_num_rows($result); $i++) {
						$grade = mysql_fetch_assoc($result);
						?>
						<tr>
							<td><?php echo $grade['roll_no']; ?></td>
							<td><?php echo $grade['course']; ?></td>
							<td><?php echo $grade['name']; ?></td>
							<td><?php echo $grade['grade']; ?></td>
							<td><?php echo $_POST['sem']; ?></td>
							<td>
								<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
								<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
							</td>
						</tr>
						<?php
					}
				?>
				</table>
			</div>
			<?php
			}  //end of inside_function() function
			
			/* This function is called when the user click the submit in search form which is index.php(admin)
			 * with any of the combinations displayed there in index.php(admin) page. Then it will show the
			 * grades list according to the combination given by the user
			 */
			function search_result() {
				if($sroll_no=$_POST['roll_no']) {               //if  the roll_no is set
					$batch = substr($sroll_no, 2, -4);
					$check = "20".$batch."";
					$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
					mysql_select_db($check) or die ("nooooo database");
					if ( $ssemester = $_POST['sem'] ) {         //roll_no and semester combination  
						$query = "SELECT * FROM ".$ssemester." WHERE roll_no='".$sroll_no."'";
						inside_function($query);
					} 
					else if ( $scourse = $_POST['course'] ) {	//roll_no and course combination
					?>
					<div align=center style="overflow:auto;height:480px">
					<table border="1" cellspacing="0" cellpadding="6">
						<tr bgcolor="#000000">
							<th><strong>Roll_No</strong></th>
							<th><strong>Course</strong></th>
							<th><strong>Student_Name</strong></th>
							<th><strong>Grade</strong></th>
							<th><strong>Semester</strong></th>
							<th>&nbsp;</th>
						</tr>
						<?php
						for ($seme=1; $seme<9; $seme++) {
							$sem = "semester_".$seme."";
							$query = "SELECT * FROM ".$sem." WHERE roll_no='".$sroll_no."' AND course='".$scourse."'";
							$result = mysql_query($query) or die(mysql_error());
							for ($i=0; $i<mysql_num_rows($result); $i++) {
								$grade = mysql_fetch_assoc($result);
								?>
								<tr>
									<td><?php echo $grade['roll_no']; ?></td>
									<td><?php echo $grade['course']; ?></td>
									<td><?php echo $grade['name']; ?></td>
									<td><?php echo $grade['grade']; ?></td>
									<td><?php echo $sem; ?></td>
									<td>
										<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
										<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
									</td>
								</tr>
								<?php	
							}
						}
						?>
					</table>
					</div>
					<?php
					}
					else {      //roll_no combination
						?>
						<div align=center style="overflow:auto;height:480px">
						<table border="1" cellspacing="0" cellpadding="6">
							<tr bgcolor="#000000">
								<th><strong>Roll_No</strong></th>
								<th><strong>Course</strong></th>
								<th><strong>Student_Name</strong></th>
								<th><strong>Grade</strong></th>
								<th><strong>Semester</strong></th>
								<th>&nbsp;</th>
							</tr>
							<?php
							for ($seme=1; $seme<9; $seme++) {
								$sem = "semester_".$seme."";
								$query = "SELECT * FROM ".$sem." WHERE roll_no='".$sroll_no."'";
								$result = mysql_query($query) or die(mysql_error());
								for ($i=0; $i<mysql_num_rows($result); $i++) {
									$grade = mysql_fetch_assoc($result);
									?>
									<tr>
										<td><?php echo $grade['roll_no']; ?></td>
										<td><?php echo $grade['course']; ?></td>
										<td><?php echo $grade['name']; ?></td>
										<td><?php echo $grade['grade']; ?></td>
										<td><?php echo $sem; ?></td>
										<td>
											<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
											<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
										</td>
									</tr>
									<?php	
								}
							}
							?>
						</table>
						</div>
						<?php
					}//end of else roll_no combination
				}//end of if case(roll_no)
				
				else if ( $sbatch = $_POST['batch'] ) {        //if batch is set
					$bat=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
					mysql_select_db($sbatch) or die ("no database");
					if ( $scourse = $_POST['course'] ) {       //if course is set
						if ( $ssemester = $_POST['sem'] ) {    //batch, course and semester combination 
							$query = "SELECT * FROM ".$ssemester." WHERE course='".$scourse."'";				
							inside_function($query);
						}
						else {								   //batch and course combination
							?>
							<div align=center style="overflow:auto;height:480px">
							<table border="1" cellspacing="0" cellpadding="6">
							<tr bgcolor="#000000">
								<th><strong>Roll_No</strong></th>
								<th><strong>Course</strong></th>
								<th><strong>Student_Name</strong></th>
								<th><strong>Grade</strong></th>
								<th><strong>Semester</strong></th>
								<th>&nbsp;</th>
							</tr>
							<?php
							for ($seme=1; $seme<9; $seme++) {
								$sem = "semester_".$seme."";
								$query = "SELECT * FROM ".$sem." WHERE course='".$scourse."'";
								$result = mysql_query($query) or die(mysql_error());
								for ($i=0; $i<mysql_num_rows($result); $i++) {
									$grade = mysql_fetch_assoc($result);
									?>
									<tr>
										<td><?php echo $grade['roll_no']; ?></td>
										<td><?php echo $grade['course']; ?></td>
										<td><?php echo $grade['name']; ?></td>
										<td><?php echo $grade['grade']; ?></td>
										<td><?php echo $sem; ?></td>
										<td>
											<a href="insidesearch.php?recordid=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>">edit</a>
											<a href="javascript:confirmDelete('insidesearch.php?roll_no=<?php echo $grade['roll_no'];?>&course=<?php echo $grade['course']; ?>')">delete</a>
										</td>
									</tr>
									<?php	
								}
							}
							?>
							</table>
							</div>
							<?php
						}
					}
					else {
						$message1 = "You have to select a Batch along with a Course";
						if ( !empty($message1) ) {
							echo '<h3 valign=top><span style="color:green">',$message1,'</span></h3><br>';
						}
					}
					mysql_close($bat);
				}
				else {
					$message2 = "Atleast select a Batch along with course";
					if ( !empty($message2) ) {
						echo '<h3 valign=top><span style="color:green">',$message2,'</span></h3><br>';
					}
				}
			}
			
			// if the used submits any of the combinations
			if (isset($_POST['submit1'])) {
				// assigning form inputs
				$sroll_no = $_POST['roll_no'];
				$scourse = $_POST['course'];
				$sbatch = $_POST['batch'];
				$ssemester = $_POST['sem'];	
				search_result();
			}
			?>
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
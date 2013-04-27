<?php 
/*  Authors: This code was implemented by T.Suresh Babu(CS08B037) and Dupelly Abhinay(CS08B015).
 *  Tools: Implementation is done by using the Database: MySql, Script: PHP, and HTML.
 *  File_name: index.php(student page)
 *  Purpose: This will allow the student to see his/her grades.
 */
?>

<?php
	session_start(); 
	$error = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
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
			</style>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<title>checking grades</title>
	<script language="JavaScript">
	
		/* this function is to get the confirmation from the user to logout*/
		function confirmLogout(){
			var agree = confirm("Are you wish to logout")
			if(agree)
				return true
			else
				return false
		}
	</script>
	</head>
	<body style="overflow:auto;">
	<?php 	
		echo "<p align=right >Welcome back, ".$_SESSION['loginuser'];
		echo "! <a href=\"./changepass.php\" >Change_Password</a>";
		echo " | <a href=\"./logout.php\" onClick=\"return confirmLogout()\">Logout</a></p>";
	?>
	<?php
	if ($_SESSION['loginuser']) {       //if the user is logged in
		?>
		<div id="container">
		<table class=first border="0" width=1090px style="table-layout:fixed">
		<col width=206>
		<col width=1>
		<col width=881>
		<tr align=center>
			<td align=center valign=top>
			<div align=center style="height:480px">
				<form action='index.php' method=POST>
					<br>
					<input name="course" type="hidden" >
					<b>Semester:</b>
					<select name="sem">
					<option value="0" <?php if (empty($sem)) echo "selected";?>>- select -</option>
					<?php
					$sem = $_POST['sem'];
					for ($seme=1; $seme<9; $seme++) {
						echo " <option";
						if ($sem=="semester_$seme") { 
							echo " selected"; 
							$seeme = $sem;
							echo "".$seeme;
						}
						echo ">","semester_$seme","</option>\n";
					}
					?>
					</select>
					<br>
					<br>
					<input class="btn" type="submit" name="submit" value="submit">
				</form>
			</div>
			</td>
			<td style="border-left: dashed;"></td>
			<td>
			<?php  
			
		function inside_function($query) { //this function is get called to show the grades list if there are any
			?>
			<div align=center style="overflow:auto;height:480px">
				<table border="1" cellspacing="0" cellpadding="6">
					<tr bgcolor="#000000">
						<th><strong>Course</strong></th>
						<th><strong>Grade</strong></th>
						<th><strong>Semester</strong></th>
					</tr>
			<?php
			$result = mysql_query($query) or die(mysql_error());
			for ($i=0; $i<mysql_num_rows($result); $i++) {
				$grade = mysql_fetch_assoc($result);
				?>
				<tr>
					<td><?php echo $grade['course']; ?></td>
					<td><?php echo $grade['grade']; ?></td>
					<td><?php echo $_POST['sem']; ?></td>
				</tr>
				<?php
				
			}
			?>
				</table>
				</div>
			<?php
		}
		if (!isset($_POST['submit'])) {    //if the user not yet specified any semester    
			$batch = substr($_SESSION['loginuser'], 2, -4);
			$check = "20".$batch."";
			$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
			mysql_select_db($check) or die ("nooooo database");
				?>
					<div align=center style="overflow:auto;height:480px">
					<table border="1" cellspacing="0" cellpadding="6">
					<tr bgcolor="#000000">
						<th><strong>Course</strong></th>
						<th><strong>Grade</strong></th>
						<th><strong>Semester</strong></th>
					</tr>
					<?php
					for ($seme=1; $seme<9; $seme++) {
						$sem = "semester_".$seme."";
						$query = "SELECT course,grade FROM ".$sem." WHERE roll_no='".$_SESSION['loginuser']."'";
						$result = mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($result) > 0) {
							for ($i=0; $i<mysql_num_rows($result); $i++) {
								$grade = mysql_fetch_assoc($result);
								$course = $grade['course'];
								$grade = $grade['grade'];
								$selsem = $sem;
								?>
								<tr>
									<td><?php echo $course; ?></td>
									<td><?php echo $grade; ?></td>
									<td><?php echo $selsem; ?></td>
								</tr>
								<?php
							}
						}
					}
			}
			if (isset($_POST['submit'])) {   //if the user specified semester
				$batch = substr($_SESSION['loginuser'], 2, -4);
				$check = "20".$batch."";
				$batch_check=mysql_connect('localhost','root') or die ("couldn't connect to MySql");
				mysql_select_db($check) or die ("nooooo database");
				if ( $scourse = $_POST['course'] ) {
					if ( $ssemester = $_POST['sem'] ) {
						$query = "SELECT course,grade FROM ".$ssemester." WHERE roll_no='".$_SESSION['loginuser']."' AND course='".$scourse."'";				
						inside_function($query);
					}
					else {
						?>
						<div align=center style="overflow:auto;height:480px">
						<table border="1" cellspacing="0" cellpadding="6">
						<tr bgcolor="#000000">
							<th><strong>Course</strong></th>
							<th><strong>Grade</strong></th>
							<th><strong>Semester</strong></th>
						</tr>
						<?php
						for ($seme=1; $seme<9; $seme++) {
							$ssemester = "semester_".$seme."";
							$query = "SELECT course,grade FROM ".$ssemester." WHERE roll_no='".$_SESSION['loginuser']."' AND course='".$scourse."'";
							$result = mysql_query($query) or die(mysql_error());
							if(mysql_num_rows($result) > 0) {
								$grade = mysql_fetch_assoc($result);
								$course = $grade['course'];
								$grade = $grade['grade'];
								$selsem = $ssemester;
								?>
								<tr>
									<td><?php echo $course; ?></td>
									<td><?php echo $grade; ?></td>
									<td><?php echo $selsem; ?></td>
								</tr>
								<?php
							}
						}
					}
				}	
				else if	($ssemester = $_POST['sem']) {
					$query = "SELECT course,grade FROM ".$ssemester." WHERE roll_no='".$_SESSION['loginuser']."'";
					inside_function($query);
				}
				else {
					?>
					<div align=center style="overflow:auto;height:480px">
					<table border="1" cellspacing="0" cellpadding="6">
					<tr bgcolor="#000000">
						<th><strong>Course</strong></th>
						<th><strong>Grade</strong></th>
						<th><strong>Semester</strong></th>
					</tr>
					<?php
					for ($seme=1; $seme<9; $seme++) {
						$sem = "semester_".$seme."";
						$query = "SELECT course,grade FROM ".$sem." WHERE roll_no='".$_SESSION['loginuser']."'";
						$result = mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($result) > 0) {
							for ($i=0; $i<mysql_num_rows($result); $i++) {
								$grade = mysql_fetch_assoc($result);
								$course = $grade['course'];
								$grade = $grade['grade'];
								$selsem = $sem;
								?>
								<tr>
									<td><?php echo $course; ?></td>
									<td><?php echo $grade; ?></td>
									<td><?php echo $selsem; ?></td>
								</tr>
								<?php
							}
						}
					}	
				}				
			}
	}
	else {
		echo "Please <a href=\"http://localhost/project\">login</a>";
		header("Location: http://localhost/project");
	}	
	?>
					</table>
					</div>
			</td>
		</tr>
	</table>
	</div>
	</body>
</html>
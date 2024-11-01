<?
// Verify and upload the resume file
if(isset($_FILES['resumefile'])) {
	$filename=str_replace("%", "", $_FILES["resumefile"]["name"]);
	$filename=str_replace("\"", "", $filename);
	$filename=str_replace("'", "", $filename);
	// Fixes the paths for Windows
	$workaround = str_replace("|", "\\", $_POST['updir']);
	$workaround = str_replace("/", "\\", $workaround);
	
	if ($_FILES["resumefile"]["type"] != "application/msword" AND $_FILES["resumefile"]["type"] != "application/pdf" AND $_FILES["resumefile"]["type"] != "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
		echo "Invalid filetype. The file must be a PDF, or a Word document.";
		echo $_FILES["resumefile"]["type"];
		exit();
	} else {
		if (file_exists($filename)) {
			echo $filename . " already exists. ";
		} else {
			move_uploaded_file($_FILES["resumefile"]["tmp_name"], "$workaround\\" . $filename);
			echo $filename;
		}
	}
	exit();
}

// Process and mail the application
if(isset($_POST['pdir'])) {
	// Make sure that necessary fields weren't skipped somehow
	// Since checks are done via javascript, this likely means a bot is responsible
	if($_POST['first'] == '' OR $_POST['last'] == '' OR $_POST['phone'] == '' OR $_POST['email'] == '' OR $_POST['address'] == '') {
		exit();
	}
	// Fixes the paths for Windows
	$workaround = str_replace("|", "\\", $_POST['pdir']);
	$workaround = str_replace("/", "\\", $workaround);
	require 'class.phpmailer.php';

	$mail = new PHPMailer;
	$mail->From = 'noreply@'.$_SERVER['HTTP_HOST'];
	$mail->FromName = 'Application Mailer';
	// Break apart and add any addresses given
	$sendTo = explode(',', $_POST['contact']);
	foreach ($sendTo as $x) {
		$mail->AddAddress($x);
	}
	$mail->WordWrap = 50;
	if($_POST['resattach'] != '' && file_exists($workaround.'\\'.$_POST['resattach'])) {
		$mail->AddAttachment($workaround.'\\'.$_POST['resattach']);
	}
	$mail->IsHTML(true);
	$mail->Subject = 'New Application for '.$_POST['jobtitle'];
	$mail->Body    = 'Hello, a new application for the '.strip_tags($_POST['jobtitle']).' position has been received! <br><br>
										<table width="100%" style="border: 1px solid #000; border-left: 0; border-radius: 4px; border-spacing: 0;">
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>First Name:</b> '.strip_tags($_POST['first']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Last Name:</b> '.strip_tags($_POST['last']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Email Address:</b> '.strip_tags($_POST['email']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Phone Number:</b> '.strip_tags($_POST['phone']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Mailing Address:</b> <br> '.strip_tags($_POST['address']).'</td>
											</tr>';
			if(isset($_POST['custom1']) && isset($_POST['custom2'])) {
			 $mail->Body .= '<tr>
											   <td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>'.strip_tags($_POST['custom1']).':</b> <br> '.strip_tags($_POST['custom2']).'</td>
											 </tr>';
											}
								 $mail->Body .= '
								 			<tr><td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000; background-color: #cccccc;"><b>Further Information</b></td></tr>
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Date Available:</b> '.strip_tags($_POST['available']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Desired Salary:</b> '.strip_tags($_POST['salary']).'</td>
											</tr>
								 			<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Experience & Knowledge:</b> <br> '.strip_tags($_POST['experience']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Special Skills:</b> <br> '.strip_tags($_POST['skills']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>US Citizen:</b> '.strip_tags($_POST['citizen']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>If no, authorized?:</b> '.strip_tags($_POST['authorized']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Willing to Relocate?:</b> '.strip_tags($_POST['relocate']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>If yes, explain:</b> '.strip_tags($_POST['relocate2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Worked for us before?:</b> '.strip_tags($_POST['previous']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>If so, which and when?:</b> '.strip_tags($_POST['previous2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Convicted of a Felony?:</b> '.strip_tags($_POST['felony']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>If yes, explain:</b> '.strip_tags($_POST['felony2']).'</td>
											</tr>';
											
									if(isset($_POST['hs']) && strlen($_POST['hs']) > 1) {
										$mail->Body .= '
								 			<tr><td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000; background-color: #cccccc;"><b>Education History</b></td></tr>
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>High School:</b> '.strip_tags($_POST['hs']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>City, State:</b> '.strip_tags($_POST['hs2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From - To:</b> '.strip_tags($_POST['hs3']).' - '.strip_tags($_POST['hs4']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Did you graduate?:</b> '.strip_tags($_POST['hs5']).'</td>
											</tr>';
										if(isset($_POST['c11']) && strlen($_POST['c11']) > 1) {
											$mail->Body .= '
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>College:</b> '.strip_tags($_POST['c11']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>City, State:</b> '.strip_tags($_POST['c12']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From:</b> '.strip_tags($_POST['c13']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>To:</b> '.strip_tags($_POST['c14']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Did you graduate?:</b> '.strip_tags($_POST['c15']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Degree:</b> '.strip_tags($_POST['c16']).'</td>
											</tr>';
											
										}
										if(isset($_POST['c21']) && strlen($_POST['c21']) > 1) {
											$mail->Body .= '
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>College:</b> '.strip_tags($_POST['c21']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>City, State:</b> '.strip_tags($_POST['c22']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From:</b> '.strip_tags($_POST['c23']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>To:</b> '.strip_tags($_POST['c24']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Did you graduate?:</b> '.strip_tags($_POST['c25']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Degree:</b> '.strip_tags($_POST['c26']).'</td>
											</tr>';
										}
										$mail->Body .= '
								 			<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Career Objectives:</b> '.strip_tags($_POST['objectives']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Special Training, Experience, or Pertinent Data:</b> '.strip_tags($_POST['etc']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>How Did You Hear About Us?:</b> '.strip_tags($_POST['referral']).'</td>
											</tr>';
									}
									
									if(isset($_POST['branch']) && strlen($_POST['branch']) > 1) {
										$mail->Body .= '
								 			<tr><td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000; background-color: #cccccc;"><b>Military Service</b></td></tr>
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Branch:</b> '.strip_tags($_POST['branch']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From - To:</b> '.strip_tags($_POST['mi1']).' - '.strip_tags($_POST['mi2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Rank at Discharge:</b> '.strip_tags($_POST['mi3']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Type of Discharge:</b> '.strip_tags($_POST['mi4']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>If other than Honorable, Explain:</b> '.strip_tags($_POST['mi5']).'</td>
											</tr>';
									}
									
									if(isset($_POST['peco1']) && strlen($_POST['peco1']) > 1) {
										$mail->Body .= '
								 			<tr><td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000; background-color: #cccccc;"><b>Previous Employment</b></td></tr>
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Company:</b> '.strip_tags($_POST['peco1']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Address:</b> '.strip_tags($_POST['pead1']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Job Title:</b> '.strip_tags($_POST['pejt1']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Phone:</b> '.strip_tags($_POST['peph1']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Supervisor:</b> '.strip_tags($_POST['pesu1']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Can Contact for Reference?:</b> '.strip_tags($_POST['peref1']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Starting Salary:</b> '.strip_tags($_POST['pess1']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Ending Salary:</b> '.strip_tags($_POST['pees1']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Responsibilities:</b> '.strip_tags($_POST['peres1']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From - To:</b> '.strip_tags($_POST['pefr1']).' - '.strip_tags($_POST['peto1']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Reason for Leaving:</b> '.strip_tags($_POST['perl1']).'</td>
											</tr>';
											if(isset($_POST['peco2']) && strlen($_POST['peco2']) > 1) {
												$mail->Body .= '
								 			<tr><td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Previous Employment</b></td></tr>
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Company:</b> '.strip_tags($_POST['peco2']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Address:</b> '.strip_tags($_POST['pead2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Job Title:</b> '.strip_tags($_POST['pejt2']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Phone:</b> '.strip_tags($_POST['peph2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Supervisor:</b> '.strip_tags($_POST['pesu2']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Can Contact for Reference?:</b> '.strip_tags($_POST['peref2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Starting Salary:</b> '.strip_tags($_POST['pess2']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Ending Salary:</b> '.strip_tags($_POST['pees2']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Responsibilities:</b> '.strip_tags($_POST['peres2']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From - To:</b> '.strip_tags($_POST['pefr2']).' - '.strip_tags($_POST['peto2']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Reason for Leaving:</b> '.strip_tags($_POST['perl2']).'</td>
											</tr>';
											}
											if(isset($_POST['peco3']) && strlen($_POST['peco3']) > 1) {
												$mail->Body .= '
								 			<tr><td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Previous Employment</b></td></tr>
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Company:</b> '.strip_tags($_POST['peco3']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Address:</b> '.strip_tags($_POST['pead3']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Job Title:</b> '.strip_tags($_POST['pejt3']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Phone:</b> '.strip_tags($_POST['peph3']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Supervisor:</b> '.strip_tags($_POST['pesu3']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Can Contact for Reference?:</b> '.strip_tags($_POST['peref3']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Starting Salary:</b> '.strip_tags($_POST['pess3']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Ending Salary:</b> '.strip_tags($_POST['pees3']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Responsibilities:</b> '.strip_tags($_POST['peres3']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From - To:</b> '.strip_tags($_POST['pefr3']).' - '.strip_tags($_POST['peto3']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Reason for Leaving:</b> '.strip_tags($_POST['perl3']).'</td>
											</tr>';
											}
											if(isset($_POST['peco4']) && strlen($_POST['peco4']) > 1) {
												$mail->Body .= '
								 			<tr><td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Previous Employment</b></td></tr>
								 			<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Company:</b> '.strip_tags($_POST['peco4']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Address:</b> '.strip_tags($_POST['pead4']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Job Title:</b> '.strip_tags($_POST['pejt4']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Phone:</b> '.strip_tags($_POST['peph4']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Supervisor:</b> '.strip_tags($_POST['pesu4']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Can Contact for Reference?:</b> '.strip_tags($_POST['peref4']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Starting Salary:</b> '.strip_tags($_POST['pess4']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Ending Salary:</b> '.strip_tags($_POST['pees4']).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Responsibilities:</b> '.strip_tags($_POST['peres4']).'</td>
											</tr>
											<tr>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>From - To:</b> '.strip_tags($_POST['pefr4']).' - '.strip_tags($_POST['peto4']).'</td>
												<td style="border-left: 1px solid #000; padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #000;"><b>Reason for Leaving:</b> '.strip_tags($_POST['perl4']).'</td>
											</tr>';
											}
									}
											
		$mail->Body .= '</table>
										<br>
										If the applicant included a resume in their submission, it has been attached to this email.';
	$mail->AltBody = 'This message requires HTML to be enabled to view it properly.';
	if(!$mail->Send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo;
	   exit;
	}
	
	// Send an automatic response if it is requested
	if(isset($_POST['reply']) && isset($_POST['email'])) {
		$reply = new PHPMailer;
		$reply->From = 'noreply@'.$_SERVER['HTTP_HOST'];
		if(isset($_POST['rname']) && strlen($_POST['rname']) > 0) {
			$reply->FromName = $_POST['rname'];	
		} else {
			$reply->FromName = 'Application Mailer';
		}
		$reply->AddAddress($_POST['email']);
		$reply->WordWrap = 50;
		$reply->IsHTML(true);
		$reply->Subject = 'Application Reception Confirmation';
		$reply->Body    = $_POST['reply'];
		$reply->AltBody = $_POST['reply'];
		if(!$reply->Send()) {
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $reply->ErrorInfo;
		   exit;
		}
	}
	
	// Remove the uploaded resume
	if ($_POST['resattach'] != '' && file_exists($workaround.'\\'.$_POST['resattach'])) {
		unlink($workaround.'\\'.$_POST['resattach']);
	}
	
	echo '<div class="alert alert-success alert-block">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>Successfully Submitted!</h4>
					Thank you for your application and interest in the position! <br>
					Your application has been successfully submitted and received. It will be reviewed, and we will contact you when we have done so.
				<div>';	
}
?>
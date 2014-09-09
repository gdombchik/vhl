<?php
$url = get_bloginfo('wpurl');

$validinputs=array('name','address','city','state','zip','country','email','phone','comments', 'patient', 'family', 'friend', 'physician', 'genetic', 'researcher');
//	 $validinputs designates valid field names - no field name injection allowed
  	          
$formvars=array();
//    $formvars contains the parsed user input where the key comes from $validinputs only

$configvars=array('subject'=>'Contact Us Form Submission',
				'from'=>'VHL Submission <handbook@vhl.org>',
				'mimeboundry'=>'Contact',
				'sendto'=>'handbook@vhl.org');

$required=array('name','country','email');

$debug=0;
/* all functions (c) 2008, 2011 David Lyle & Angela Render - licensed to use
	david@thunderpaw.com, angela@angelarender.com
	*/

function form_generate_blankform(){
	global $formvars;
	?>
	<p>You may download a copy of the VHL Handbook with our best wishes for our good health. Please let us konw who you are by filling out the voluntary form below. <strong>Country is required.</strong> The &quot;Submit&quot; button is at the bottom of the form.</p>
	<p>This information is <strong>entirely</strong> confidential.</p>
	<p>If you provide your mailing address, we will send you more information. If you provide an email address it will allow us to stay in touch. However we will not allow anyone else to know your name or address.</p>
	<p>Please also consider helping us with a donation. We depend on individual contributions. To donate any amount, <a href="https://donatenow.networkforgood.org/1411829?code=orange">click here</a>.</p>
	<p>Fill out this form and Submit it, or use telephone or e-mail or paper mail -- whatever works best for you!<br>&nbsp;<br>
    Fields marked with a star (*) are required<br>&nbsp;<br>
	<form method="post" action="">
    <table>
    <tr><td>* Name: </td><td><input maxlength="45" size="30" type="text" name="var[name]" value="<?php echo $formvars[name];?>"></td></tr>
    <tr><td>Address: </td><td><input maxlength="45" size="30" type="text" name="var[address]" value="<?php echo $formvars[address];?>"></td></tr>
    <tr><td>City: </td><td><input maxlength="45" size="30" type="text" name="var[city]" value="<?php echo $formvars[city];?>"></td></tr>
    <tr><td>State: </td><td><input maxlength="45" size="30" type="text" name="var[state]" value="<?php echo $formvars[state];?>"></td></tr>
    <tr><td>Zip: </td><td><input maxlength="45" size="30" type="text" name="var[zip]" value="<?php echo $formvars[zip];?>"></td></tr>
    <tr><td>* Country: </td><td><input maxlength="45" size="30" type="text" name="var[country]" value="<?php echo $formvars[country];?>"></td></tr>
    <tr><td>Phone:  </td><td><input maxlength="45" size="30" type="text" name="var[phone]" value="<?php echo $formvars[phone];?>"></td></tr>
	<tr><td>* Email:  </td><td><input maxlength="45" size="30" type="text" name="var[email]" value="<?php echo $formvars[email];?>"></td></tr>
	<tr>
		<td>I am a:</td><td>
			<input type="checkbox" id="chkPatient" name="var[patient]" value="Patient" /> <label for="chkPatient">Patient</label><br />
			<input type="checkbox" id="chkFamily" name="var[family]" value="Family" /> <label for="chkFamily">Family Member</label><br />
			<input type="checkbox" id="chkFriend" name="var[friend]" value="Friend" /> <label for="chkFriend">Friend</label><br />
			<input type="checkbox" id="chkPhysician" name="var[physician]" value="Physician" /> <label for="chkPhysician">Physician</label><br />
			<input type="checkbox" id="chkGenetic" name="var[genetic]" value="Genetic Counselor" /> <label for="chkGenetic">Genetic Counselor</label><br />
			<input type="checkbox" id="chkResearcher" name="var[researcher]" value="Researcher" /> <label for="chkResearcher">Researcher</label><br />
			 
		</td>
	</tr>
	<tr><td>Comments:  </td><td><textarea maxlength="400" cols="50" rows="4" size="30" name="var[comments]" value="<?php echo $formvars[comments];?>"></textarea></td></tr>
    <tr><td colspan="2" align="center"><span style="display: none"><input maxlength="10" size="10" type="text" name="var[spam]" value="Spam"><br></span>
	<input type="submit" name="submit" value="submit">
    </form>
    </td></tr>
    </table>
	<?php
}
	
function isValidEmail($email)
{
  	$valid = true;
	$pattern = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' .
				'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
	//  removed eregi due to depreciated eregi command 8/2009 DRL if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
	if (!preg_match($pattern, $email)) {
        $valid = false;
    } 
    return $valid;
}

function validate_input(){
	global $formvars;
	global $required;
	global $errors;
	global $error_email;
	$errorcount=0;
	for ($x=0;$x<count($required);$x=$x+1){
		if(!IsSet($formvars["$required[$x]"]) ){
			$errorcount=$errorcount+1;
			$errors[$errorcount]=$required[$x];
			//echo "error: $required[$x]<br>"; 
		}
		if(empty($formvars["$required[$x]"]) ){
			$errorcount=$errorcount+1;
			$errors[$errorcount]=$required[$x];
			//echo "error: $required[$x]<br>"; 
		}
	}
	$error_email=0;
	//echo "test: ".$formvars["email"];
	if (!isValidEmail($formvars["email"])){
		$error_email=1;
		$errorcount=$errorcount+1;
	}
	return !$errorcount;
}

function form_generate_thankyou(){
	global $url;
	
	?>
     <h2>Printable copy of the VHL Handbook</h2>
      <p>This file is in a format known as pdf, or Adobe Acrobat. This format 
        captures the formatting of the newsletter. With a simple viewer which 
        is widely available you should be able to view the file online, or print 
        it out to your own printer. Acrobat is available for Windows, Mac and 
        Unix.</p>
      <p>Do you have Acrobat already installed? If yes, proceed. If no, click 
        <a href="http://get.adobe.com/reader/" target="_blank">here <img src="<?php echo $url?>/images/get_adobe_reader.jpg" ></a> 
      <dl>
        <dt>When you're ready to download the handbook, click below:</dt>
      </dl>
      <p><a href="http://www.vhl.org/wordpress/library/handbook-41.pdf"> <img src="<?php echo $url?>/images/pdficon.gif" border="0" > 
        The VHL Handbook, 2014 Edition</a> (in English), 1001K and will take approximately 8 minutes at 28,800.</p>
      <p>
      	Also available for download is the <a href="http://www.vhl.org/wordpress/library/vhlkidsbook-w-cover.pdf">VHL Handbook Kid's Edition <img src="<?php echo $url?>/images/pdficon.gif" border="0" ></a>
      </p>
      <dl>
        <p>You can read it online or print it out, as you wish. Please <a href="mailto:info@vhl.org">let 
          us know</a> whether this is a good format for you.</p>
      </dl>      
      <p>This booklet is also available in print from <a href="/patients-caregivers/resources/vhl-handbooks/">the VHL store.</a></p>
	<?php
}

function form_get_inputs(){
	global $validinputs,$formvars,$debug;
	
	$lenfields=sizeof($validinputs);
	$x=0;
	while ($x < $lenfields){
		$t = $validinputs[$x];
		if (IsSet($_POST['var'][$t])){
			$temp=strip_tags(trim($_POST['var'][$t]));
			$temp=str_replace("/"," ",$temp);
			$temp=str_replace("\\"," ",$temp);
			$formvars[$t]=$temp;
			
			//echo "var: " . $t . "=" . $formvars[$t] . "<br>";
		}
		$x=$x+1;
	}	
}

function show_errors(){
	global $errors;
	global $error_email;
?>
	<strong style="color:#9E0000">ATTENTION FORM NOT SUBMITTED - PLEASE MAKE SURE ALL REQUIRED FIELDS (*) ARE FILLED OUT</strong><br>
<?php
	if ($error_email==1){
		echo '<strong style="color:#9E0000">Valid email required</strong><br /><br />';
	}
}

function form_send(){
	global $formvars, $configvars;
	$message="";
	foreach ($formvars as $key =>$val)
	{
		if (strcasecmp($key, "patient") == 0)
		{
			if (!empty($val))
				$message .= "I am a Patient\r\n";
		} 		
		else if (strcasecmp($key, "Family") == 0)
		{
			if (!empty($val))
				$message .= "I am a Family\r\n";
		} 
		else if (strcasecmp($key, "Friend") == 0)
		{
			if (!empty($val))
				$message .= "I am a Friend\r\n";
		} 
		else if (strcasecmp($key, "Physician") == 0)
		{
			if (!empty($val))
				$message .= "I am a Physician\r\n";
		} 
		else if (strcasecmp($key, "genetic") == 0)
		{
			if (!empty($val))
				$message .= "I am a Genetic Counselor\r\n";
		} 
		else if (strcasecmp($key, "Researcher") == 0)
		{
			if (!empty($val))
				$message .= "I am a Researcher\r\n";
		} 
		else {
			$message .= $key.": ".$val."\r\n";
		}
	}
	$headers = "From: " . $configvars[from] . "\r\n";
	
	wp_mail($configvars[sendto], $configvars[subject], $message, $headers);
}


function form_main(){
	global $formvars,$configvars,$validinputs;
	$hassubmitted=isset($_POST['submit']);
	if($hassubmitted)
	{
		form_get_inputs();
		$inputok=(validate_input());
		if ($inputok==1){
			form_send();
			form_generate_thankyou();
		}else{
			show_errors();
			$hassubmitted=0;
		}
		
	} 
	if(!$hassubmitted){
		form_generate_blankform();
	}
}

?>

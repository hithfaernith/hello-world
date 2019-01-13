<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8" />
    <title>Emailing Form Data</title>
	<style type="text/css">
	/* The CSS is here to keep the demo simple. As always, I recommend you put your CSS in an external file. */
	body {
		font: 100%/1.3 sans-serif;
		padding: 1em 2em;
	}
	</style>
</head>
<body>

<?php
/*
-----------------------------------------------------------------
IMPORTANT NOTE:

The sample PHP code in this file is meant for demonstration purposes only and is simple by design, and as a result, it doesn't include the security checks that a bulletproof script would include. Given that, I don't recommend you use it as-is on your site. If you do, of course, all legal disclaimers apply in that you agree to take on all responsibility of having used it.

If you do intend to use a script like this, I recommend consulting PHP books and other resources to learn how to check submitted form values for malicious data before you write the values to the screen or to a database, or send them via email, as well as any other methods that will make it secure.

Also, please be aware that I am not an expert PHP developer, so undoubtedly there are better ways to write this script beyond the missing security measures I noted previously.
--------------------------------------------------------------------


ABOUT THIS SCRIPT
-----------------
This sample PHP script collects each bit of information that was sent when form.html was submitted (by using the "Create Acount" button). Then it emails that data to the email addresses you specify near the bottom of this script. It collects the data in the same way that show-data.php does--by getting the value associated with each field name. The names are the name attributes assigned to the form fields in the HTML form.

if (empty($_POST)) {
	print "<p>No data was submitted.</p>";
	exit();
}

/* Creates function that removes magic escaping, if it's been applied, from values and then removes extra newlines and returns to foil spammers. Thanks Larry Ullman! */
function clear_user_input($value) {
	if (get_magic_quotes_gpc()) $value=stripslashes($value);
	$value= str_replace( "\n", '', trim($value));
	$value= str_replace( "\r", '', $value);
	return $value;
}


/* Create body of email message by cleaning each field and then appending each name and value to it. */

$body = "Here is the data that was submitted:\n";

// Get value for each form field
foreach ($_POST as $key => $value) {
	// True if for field is anything but one of the Email sign-up checkboxes
	if ($key != 'email_signup') {
		$key = clear_user_input($key);
		$value = clear_user_input($value);

		$body .= "$key: $value\n";
	} else { // True if an Email checkbox chosen	
		if (is_array($_POST['email_signup'])) {
			$body .= "$key: ";
			$counter =1;
			
			foreach ($_POST['email_signup'] as $value) {
				//Add comma and space until last element
				if (sizeof($_POST['email_signup']) == $counter) {
					$body .= "$value\n";
					break;
				} else {
					$body .= "$value, ";
					$counter += 1;
				}
			} // end foreach
		} // end inner if
	} // end else
} // end foreach

extract($_POST);

/* Get file upload picture name */
if(isset($_FILES['picture'])) {

	$picture_name = $_FILES['picture']['name'];

	// make sure name isn't blank
	if ($picture_name != '') {
		// add the picture name to the email body message
		$body .= "picture: $picture_name\n";
	}
}


/* Removes newlines and returns from $email and $name so they can't smuggle extra email addresses for spammers */
$email = clear_user_input($email);
$first_name = clear_user_input($first_name);

/* Create header that puts email in From box along with name in parentheses and sends Bcc to alternate address. Change yourmail@youremaildomain.com to the Bcc email address you want to include. */
$from='From: '. $email . "(" . $first_name . ")" . "\r\n" . 'Bcc: wee.chun.hui.2018@vjc.sg' . "\r\n";

// Creates intelligible subject line that also shows you where it came from
$subject = 'New Profile from Website';

/* Sends mail to the address below with the form data submitted above. Replace yourmail@youremaildomain.com with the email address to which you want the data sent. */
mail('vj.vrmc@gmail.com', $subject, $body, $from);


// This message will appear in the browser, not as part of the email
print "<p>Thanks for the message! We will get back to you as soon as we can.</p>";
?>

</body>
</html>

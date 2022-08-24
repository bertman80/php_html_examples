<?php
/*
Google reCAPTCHA on a form.
Go to: https://www.google.com/recaptcha/admin
Make a new by clicking on +
Choose: reCAPTCHA v2
And then select an option like 'i'm nog a robot'
Add the domain name.

See this one live: https://www.trebnie.nl/test/google_recaptcha.php
*/

// basic html
echo "
<html>
  <head>
    <title>Test reCAPTCHA</title>
  </head>
<body>
";

// include script for the reCAPTCHA
echo "
<script src='https://www.google.com/recaptcha/api.js'></script>";

// function: reCaptcha
function reCaptcha($recaptcha){
  $secret = '<SECRETKEY>';
  $ip = $_SERVER['REMOTE_ADDR'];

  $postvars = array('secret'=>$secret, 'response'=>$recaptcha, 'remoteip'=>$ip);
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
  $data = curl_exec($ch);
  curl_close($ch);

  return json_decode($data, true);
}

// form
echo "
<form method='post' action=" .$_SERVER['PHP_SELF'].">
    <div class='g-recaptcha brochure__form__captcha' data-sitekey='<SITE_KEY>'></div>
    <label for='lbl_username'>Username</label><br>
    <input type='text' name='username'><br>
    <label for='lbl_password'>Password</label><br>
    <input type='password' name='password'><br>
    <button type='submit' value='Submit'>Logon</button>
</form>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $recaptcha = $_POST['g-recaptcha-response'];
  $res = reCaptcha($recaptcha);
  if($res['success']){
    echo "Successfull";
  }else{
    echo "Error";
  }
}

// basic html
echo "
</body>
</html>
";
?>

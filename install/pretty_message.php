<?php
/**
 * This utility displays error messages of installation into an HTML document
 * @copyright  Copyright (c) 2014 PHPBack
 * @author       Benjamin BALET<benjamin.balet@gmail.com>
 * @license      http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link            https://github.com/ivandiazwm/phpback
 * @since         1.2.1
 */

/**
 * Return the base URL of PHPBack stripped off the install folder
 * @return string Base URL
 * @author Benjamin BALET<benjamin.balet@gmail.com>
 */
function getBaseUrl(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    $base = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $base = substr($base, 0, strpos($base, '/install'));
    return $base;
}

/**
 * Display a message into a basic HTML document
 * @param string $message Message to be displayed
 * @author Benjamin BALET<benjamin.balet@gmail.com>
 */
function displayMessage($message) {
    echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta charset="utf-8">
  <link rel="icon" type="image/x-icon" href="favicon.ico" sizes="16x16">
        
  <title>PHPBack Installation</title>
  <link href="../public/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="../public/bootstrap/css/prettify.css" rel="stylesheet">
  <link href="../public/css/flat-ui.css" rel="stylesheet">
  <link href="../public/css/demo.css" rel="stylesheet">
  <script src="../public/js/jquery-2.0.3.min.js"></script>
  <script src="../public/js/bootstrap.min.js"></script>

  <style type="text/css">
  a:hover{
    text-decoration: none;
  }
  body{
        background-color: #1ABC9C;
  }
  .phpback_logo{
	text-align: center;
	margin-bottom: 15px;
}
  </style>
</head>
<body>
    <div class="login-screen">
        <div class="login-form">
            <div class="phpback_logo">
                <img src="../public/img/logo_free.png" />
            </div>
            <h6>Your PHPBack installation is complete</h6>
            <p>' . $message . '</p>
        </div>
    </div>
</div>
</body>
</html>';
}

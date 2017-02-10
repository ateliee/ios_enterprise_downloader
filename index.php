<?php
require_once (dirname(__FILE__)."/config.php");
if(require_once (dirname(__FILE__)."/download.php")){
    exit;
}
function redirect(){
    $url = $_SERVER["REQUEST_URI"];
    header('HTTP/1.1 301 Moved Permanently');
    header("Location : ".$url);
    exit;
}

$USER = false;
if(isset($_SESSION[LOGIN_SESSION])){
    $time = time() - intval($_SESSION[LOGIN_SESSION]);
    if($time > TIMEOUT){
        unset($_SESSION[LOGIN_SESSION]);
        $_SESSION[FLASH_MESSAGE] = ["Timeout!"];
        redirect();
    }
    $USER = true;
}
if(strtoupper($_SERVER["REQUEST_METHOD"]) == "POST"){
    $login = false;
    $postvalue = $_POST;
    if(!empty($postvalue["username"]) && !empty($postvalue["password"])){
        if($postvalue["username"] == LOGIN_USER && $postvalue["password"] == LOGIN_PASSWORD){
            $login = true;
        }
    }
    $_SESSION[POST_VALUE] = $postvalue;
    if($login){
        $_SESSION[LOGIN_SESSION] = time();
        redirect();
    }else{
        $_SESSION[FLASH_MESSAGE] = ["Login Failure."];
    }
}
$POST_VALUE = null;
if(isset($_SESSION[POST_VALUE])){
    $POST_VALUE = $_SESSION[POST_VALUE];
    unset($_SESSION[POST_VALUE]);
}
$FLASH_MESSAGE = [];
if(isset($_SESSION[FLASH_MESSAGE])){
    $FLASH_MESSAGE = $_SESSION[FLASH_MESSAGE];
    unset($_SESSION[FLASH_MESSAGE]);
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <title>Enterprise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="robots" content="noindex">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .card-container.card {
            max-width: 460px;
            padding: 40px 20px;
        }

        .form-signin-title{
            text-align: center;
            margin-bottom: 20px;
        }
        .form-signin .input-group{
            margin-bottom: 20px;
        }
        .btn {
            font-weight: 700;
            height: 36px;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            cursor: default;
        }

        /*
         * Card component
         */
        .card {
            background-color: #F7F7F7;
            /* just in case there no content*/
            padding: 20px 25px 30px;
            margin: 0 auto 25px;
            margin-top: 50px;
            /* shadows and rounded borders */
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        }

        .btn.btn-signin {
            /*background-color: #4d90fe; */
            background-color: rgb(104, 145, 162);
            /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
            padding: 0px;
            font-weight: 700;
            font-size: 14px;
            height: 36px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border: none;
            -o-transition: all 0.218s;
            -moz-transition: all 0.218s;
            -webkit-transition: all 0.218s;
            transition: all 0.218s;
        }

        .btn.btn-signin:hover,
        .btn.btn-signin:active,
        .btn.btn-signin:focus {
            background-color: rgb(12, 97, 33);
        }
    </style>
</head>
<body>
<?php if($USER): ?>
    <div class="container" style="padding-top: 40px;">
        <h2 style="text-align: center; margin-bottom: 30px;"><?php echo ADMIN_TITLE; ?></h2>
        <?php $i = 0; foreach($DOWNLOAD_FILES as $file => $data): ?>
            <p>
                <a href="itms-services://?action=download-manifest&url=<?php echo (sprintf(PLIST_URL,$file)); ?>" class="btn btn-block <?php echo ($i == 0) ? "btn-primary" : "btn-danger"; ?>">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <?php echo $data["btn"]; ?>
                </a>
            </p>
            <?php $i++; endforeach; ?>
    </div>
<?php else: ?>
    <div class="container">
        <div class="card card-container">
            <form class="form-signin" method="post">
                <h2 class="form-signin-title">Download Files</h2>
                <?php foreach ($FLASH_MESSAGE as $message): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        <span class="sr-only">Error:</span>
                        <?php echo $message; ?>
                    </div>
                <?php endforeach; ?>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user-o" aria-hidden="true"></i></span>
                    <input type="text" name="username" id="inputUsername" class="form-control" placeholder="username" value="<?php isset($POST_VALUE["username"]) ? $POST_VALUE["username"] : ""; ?>" required="required" autofocus="autofocus">
                </div>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key" aria-hidden="true"></i></span>
                    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="password" value="<?php isset($POST_VALUE["username"]) ? $POST_VALUE["username"] : ""; ?>" required="required">
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">ログイン</button>
            </form><!-- /form -->
        </div><!-- /card-container -->
    </div><!-- /container -->
<?php endif ?>
<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>

</body>
</html>
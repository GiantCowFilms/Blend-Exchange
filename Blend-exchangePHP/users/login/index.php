 <?php
    session_start();
 
    include_once($_SERVER["DOCUMENT_ROOT"]."/parts/logger.php");
    logger("LOGIN_TRY IP_HASH:".hash("sha256",$_SERVER['REMOTE_ADDR'], false),$_SERVER["DOCUMENT_ROOT"]."/logs/","login.log");
    
    $username = $_GET["username"];
    $password = $_GET["password"];

    
    $secretKeys = json_decode(file_get_contents("../../secret/secret.json"));
    
    include("../../parts/database.php");
    
    $userData = $db->prepare("SELECT `id`,`admin` FROM `users` WHERE `username`=:username AND `password`=:password");
    $userData->execute(array('username' => $username,"password" => $password));
    //Not the best way to check for results, doing the login check in a query might be bad, but it sure is fast!
    if($userData->rowCount() == 1){
        $userData = $userData->fetchAll(PDO::FETCH_ASSOC)["0"];
        $userId = $userData["id"];
        $admin = $userData["admin"];
        //Set session
        $_SESSION["loggedIn"] = true;
        $_SESSION["userId"] = $userId;
        $_SESSION["admin"] = $admin > 0;
        //Create long term cookie
        //Sleeper Cookie
        setcookie("extendLogin", $userId.";".hash("sha256", $password.$userId.$username, false), time() + (86400 * 30), "/");
        
        //Send status
        logger("LOGIN_SUCCESS IP_HASH:".hash("sha256",$_SERVER['REMOTE_ADDR'], false),$_SERVER["DOCUMENT_ROOT"]."/logs/","login.log");
        echo '{
            "status": 1,
            "message": "You are logged in"
        }';
    } else {
        //Send status
        echo '{
            "status": 0,
            "message": "Login failed"
        }';
        logger("LOGIN_FAIL IP_HASH:".hash("sha256",$_SERVER['REMOTE_ADDR'], false),$_SERVER["DOCUMENT_ROOT"]."/logs/","login.log");
    }
    
?>

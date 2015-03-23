 <?php
    session_start();
    
    $changePassword = true;
    
    if(isset($_GET["username"])){
    $username = $_GET["username"];
    }
    if(isset($_GET["email"])){
    $email = $_GET["email"];
    }
    if(isset($_GET["oldPassword"])){
        $oldPassword = $_GET["oldPassword"];
    } else {
        $changePassword = false;
    }
    if(isset($_GET["password"])){
        $password = $_GET["password"];
    } else {
        $changePassword = false;
    }
    if(isset($_GET["confirmPassword"])){
    $passwordConfirm = $_GET["confirmPassword"];
    } else {
        $changePassword = false;
    }
    
    
    $id = $_GET["id"];
    
    include_once($_SERVER["DOCUMENT_ROOT"]."/parts/logger.php");
    logger("UPDATE_TRY ID:".$id." IP_HASH:".hash("sha256",$_SERVER['REMOTE_ADDR'], false),$_SERVER["DOCUMENT_ROOT"]."/logs/","update.log");
    
    if($changePassword == true){
        if ($password!= $passwordConfirm){
            echo '{
                "status": 0,
                "message": "Passwords do not match"
            }';
            exit();
        }
        //Check if it is a sha256 hash, if not, throw in the towel!
        if(!preg_match('/^[A-Fa-f0-9]{64}$/',$password)){
            echo '{
                "status": 0,
                "message": "Passwords is not correct. Please limit to between 5-20 characters, that fall under this set: a-z,0-9,_,-,!,@,#,$,%,^,&,"
            }';
            exit();
        };
        if(!preg_match('/^[A-Fa-f0-9]{64}$/',$oldPassword)){
            echo '{
                "status": 0,
                "message": "Passwords is not correct. Please limit to between 5-20 characters, that fall under this set: a-z,0-9,_,-,!,@,#,$,%,^,&,"
            }';
            exit();
        };
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '{
                "status": 0,
                "message": "Please include a valid email"
            }';
            exit();
        };
    }
    $secretKeys = json_decode(file_get_contents("../../secret/secret.json"));
    
    include("../../parts/database.php");
    $userData = $db->prepare("SELECT `password` FROM `users` WHERE `id`=:uid");
    $userData->execute(array('uid' => $id));    
    $userData = $userData->fetchAll(PDO::FETCH_ASSOC);
    $matchPassword = $userData["0"]["password"];
    if($changePassword == true){
        if($matchPassword == $oldPassword){
            $userData = $db->prepare("UPDATE `users` SET `password`=:password WHERE `id`=:uid LIMIT 1");
            $userData->execute(array("password" => $password, "uid" => $id ));
        } else {
            echo '{
            "status": 0,
            "message": "Passwords do not match"
            }';
            exit();
        }
    }
    
    $userData = $db->prepare("UPDATE `users` SET `email`=:email,`username`=:username WHERE `id`=:uid LIMIT 1");
    $userData->execute(array('username' => $username,"email" => $email, "uid" => $id ));
    //Not the best way to check for results, doing the login check in a query might be bad, but it sure is fast!
        //Send status
        echo '{
            "status": 1,
            "message": "Account Updated"
        }';
        logger("UPDATE_SUCCESS DETAILS:[EMAIL:".$email.";USERNAME:".$username."] ID:".$id." IP_HASH:".hash("sha256",$_SERVER['REMOTE_ADDR'], false)."/logs/","update.log");
    
?>

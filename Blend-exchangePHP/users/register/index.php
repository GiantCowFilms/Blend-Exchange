 <?php
    session_start();
 
    $username = $_GET["username"];
    $email = $_GET["email"];
    $password = $_GET["password"];
    $passwordConfirm = $_GET["confirmPassword"];
    if ($password == $passwordConfirm){
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
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         echo '{
            "status": 0,
            "message": "Please include a valid email"
        }';
     };
     
    $secretKeys = json_decode(file_get_contents("../../secret/secret.json"));
    
    include("../../parts/database.php");

    $userData = $db->prepare("INSERT INTO `users` SET `admin`=0,`email`=:email,`password`=:password,`username`=:username");
    $userData->execute(array('username' => $username,"email" => $email,"password" => $password));
    $userId = $db->lastInsertId("Id");
    //Not the best way to check for results, doing the login check in a query might be bad, but it sure is fast!
        //Set session
        $_SESSION["loggedIn"] = true;
        $_SESSION["userId"] = $userId;
        $_SESSION["admin"] = false;
        //Send status
        echo '{
            "status": 1,
            "message": "You are registered"
        }';
    
?>

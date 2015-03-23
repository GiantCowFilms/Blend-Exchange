<?php 
include($_SERVER["DOCUMENT_ROOT"]."/parts/checkLogin.php");
include($_SERVER["DOCUMENT_ROOT"]."/parts/database.php");

//Query created with the help of TehShrike http://stackoverflow.com/users/201789/tehshrike
$userData = $db->prepare("SELECT `id`,`username`,`email` FROM `users` WHERE `id`=:uid");
$userData->execute(["uid" => $userId]);
$userData = $userData->fetchAll(PDO::FETCH_ASSOC)["0"];
//add autoFlag catches
?>
<form id="registerForm" style="width: 400px;">
    <div id="updateFormSuccess" class="nwInfo noticeWarning" style="display: none; margin-bottom: 10px;">
        Your information was updated
    </div>
        <div id="updateFormError" class="nwDanger noticeWarning" style="display: none; margin-bottom: 10px;">
        Your information was updated
    </div>
    <input id="username" class="txtBlue bodyStack" placeholder="Username" value="<?php echo $userData["username"]; ?>"/>
    <input id="email" class="txtBlue bodyStack" placeholder="Email" value="<?php echo $userData["email"]; ?>"/>
    <div class ="bodyStack"><a id="changePassword" style="cursor: pointer;">Change password</a></div>
    <div id="changePasswordForm" style="display: none;">
        <input type="password" id="oldPassword" class="txtBlue bodyStack" placeholder="Old Password" />
        <input type="password" id="password" class="txtBlue bodyStack" placeholder="New Password" />
        <input type="password" id="confirmPassword" class="txtBlue bodyStack" placeholder="Confirm New Password" />
    </div>
    <div class ="bodyStack">All changed fields will be updated</div>
    <div class="btnBlue" id="updateAccount" style="width: 100%; max-width: none;">
        Update
    </div>
</form>
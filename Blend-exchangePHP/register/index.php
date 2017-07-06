    <?php 
    include("../parts/checkLogin.php");
    if($loggedIn == false){
        include("../parts/head.php");
        //Just add body tags, I want the login form to be more independent
        echo '<body><div id="mainContainer" style="margin: auto;">';
        include("../parts/registerForm.php"); 
        echo "</div><body/></html>";
        exit();
    }
    
    ?>
        <?php include("../parts/header.php"); ?>
    <?php include("../parts/admin/getFlaggedFiles.php"); ?>
        <div id="mainContainer">
            <div class="noticeWarning nwInfo">
                You are registered!
            </div>
            <a href="/users/<?php echo $userId ?>/">Go to your account>></a>
        </div>
        <?php include("../parts/footer.php"); ?>
        <script src="/jquery.js"></script>
    </body>
</html>
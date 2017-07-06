    <?php 
    $requireAdmin = false;
    include("../parts/requireLogin.php"); ?>
    <?php include("../parts/header.php"); ?>
    <?php //include("../parts/admin/getFlaggedFiles.php"); ?>
    <?php if(isset($_GET["returnUrl"])){
                  header('Location: '.$_GET["returnUrl"]);
      };?>
        <div id="mainContainer">
            <div class="noticeWarning nwInfo">
                You are logged in!
            </div>
            <a href="/users/<?php echo $userId ?>/">Go to your account>></a>
        </div>
        <?php include("../parts/footer.php"); ?>
        <script src="/jquery.js"></script>
        <script src="/dropzone.js"></script>
        <script src="/upload.js"></script>
    </body>
</html>
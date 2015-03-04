<html>
    <?php include("../../parts/requireLogin.php"); ?>
    <?php include("../../parts/header.php"); ?>
    <?php 
    include("../../parts/database.php");

    //Autoflag:
    //Looks for blends that have less then half of there refs from thier question link
    //Query created with the help of TehShrike http://stackoverflow.com/users/201789/tehshrike
    $files = $db->prepare("SELECT `id`,`fileName`,`questionLink`, `date` FROM `blends` ORDER BY `date` DESC LIMIT 0,100 ");
    $files->execute();
    $files = $files->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <div id="mainContainer">
            <a href="/admin/"><-Back</a>
            <h2>All files:</h2>
            <ul>
                <?php
                foreach ($files as $file)
                    {
                        echo "<li><a href='/b/".$file["id"]."/'>".$file["fileName"]."</a> - <a href='".$file["questionLink"]."'>Question</a> uploaded on ".$file["date"]."</li>";
                    }
                    
                ?>
            </ul>
        </div>
        <?php include("../../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>
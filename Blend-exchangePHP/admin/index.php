<html>
    <?php include("../parts/requireLogin.php"); ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/admin/getFlaggedFiles.php"); ?>
        <div id="mainContainer">
            <h2>Flagged Files:</h2>
            <ul>
                <?php
                foreach ($files as $file)
                    {
                        echo "<li><a href='/b/".$file["id"]."/'>".$file["fileName"]."</a> - <a href='".$file["questionLink"]."'>Question</a> - Flagged: ".$file["val"]."</li>";
                    }
                    
                ?>
            </ul>
        </div>
        <?php include("../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>
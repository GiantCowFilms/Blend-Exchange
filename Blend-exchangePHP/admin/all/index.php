<html>
    <?php 
    $requireAdmin = true;
    include("../../parts/requireLogin.php"); ?>
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
            <table>
                <thead>
                    <tr>
                        <th>File Name</th><th>Question</th><th>Upload Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($files as $file)
                    {
                        echo "<tr><td><a href='/b/".$file["id"]."/'>".$file["fileName"]."</a></td><td><a href='".$file["questionLink"]."'>".substr($file["questionLink"], 32, 60)."</a></td><td>".$file["date"]."</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php include("../../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>
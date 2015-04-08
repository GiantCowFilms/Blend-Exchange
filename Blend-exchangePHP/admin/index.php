<html>
    <?php 
    $requireAdmin = true;
    include("../parts/requireLogin.php"); ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/admin/getFlaggedFiles.php"); ?>
        <div id="mainContainer">
            <a href="/admin/all/">All files</a><br><br>
            <a href="/admin/users/">Users</a>
            <h2>Flagged Files:</h2>
            <table>
                <thead>
                    <tr>
                        <th>File Name</th><th>Question</th><th>Flagged</th><th>Flag Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($files as $file)
                    {
                        echo "<tr><td><a href='/b/".$file["id"]."/'>".$file["fileName"]."</a></td><td><a href='".$file["questionLink"]."'>".substr($file["questionLink"], 32, 60)."</a></td><td>".$file["val"]."</td><td>".$file["date"]."</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <h2>Auto Flagged Files:</h2>
                        <table>
                <thead>
                    <tr>
                        <th>File Name</th><th>Question</th><th>Flagged</th><th>Valid</th><th>Invalid</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($autoFlags as $autoFlag)
                {
                    echo "<tr><td><a href='/b/".$autoFlag["id"]."/'>".$autoFlag["fileName"]."</a></td><td><a href='".$autoFlag["questionLink"]."'>".substr($autoFlag["questionLink"], 32, 60)."</a></td><td>".$autoFlag["val"]."</td><td>".$autoFlag["validRefs"]."</td><td>".$autoFlag["invalidRefs"]."</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php include("../parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>
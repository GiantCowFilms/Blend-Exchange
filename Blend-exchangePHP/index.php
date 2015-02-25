<html>
    <?php include("parts/header.php"); ?>
        <div id="uploadContainer">
            <?php include("parts/uploadPage.php"); ?>
            <div id="usageNotice">
                <h2>
                    Notice:
                </h2>
                This service is for blender.stackexchange questions and answers only. Files for other uses will be removed. The purpose of this site is to allow users to upload .blends for to help their blender.stackexchange posts. Please flag all violating posts.
            </div><br />
            <span>By using this service you agree to our <a href="/terms">terms of service</a></span>
        </div>
        <?php include("parts/footer.php"); ?>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script src="upload.js"></script>
    </body>
</html>

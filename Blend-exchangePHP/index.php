<html>
    <?php include("parts/header.php"); ?>
        <div id="uploadContainer">
            <form id="uploadTarget" class="bodyStack">
                <div id="uploadText">
                    <div class="centerText">
                        Drag a file here to upload a .blend<br>or click to browse
                    </div>
                </div>
                    <div id="uploadArea">

                    </div>
            </form>
            <div id="uploadOptions">
                <input class="txtBlue bodyStack" id="questionUrl" placeholder="Enter the url of the queston on blender.stackexchange"/>
                
                <div>
                    <input type="password" class="txtBlue bodyStack" id="password" placeholder="Enter a password to enable editing or deleting (optional)"/>
                </div>
                <div id="cancel" class="btnBlue bodyStack" style="width: 295px; display: inline-block;">
                    Cancel
                </div><div id="upload" class="btnBlue bodyStack" style="width: 295px; margin-left: 10px; display: inline-block;">
                    Upload
                </div>
            </div>
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
        <script>
            var blendDropzone = new Dropzone("#uploadTarget", { url: "/finish/", clickable: ["#uploadTarget", ".centerText", "#uploadText"], maxFilesize: 50, autoProcessQueue: false, acceptedFiles: ".blend", uploadMultiple: false, previewTemplate: '<div><div><h2 data-dz-name>Name.blend</h2><div class="progressContainer"  role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" ><div class="progress"style="width:0%;" data-dz-uploadprogress></div></div><span data-dz-size>- 3.5MB</span></div></div>', previewsContainer: "#uploadArea",maxFiles: 1 });
            blendDropzone.on("addedfile", function () {
                $("#uploadText").hide();
            });
            blendDropzone.on("maxfilesexceeded", function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });
            $(".centerText").click(function (e) {
                e.stopPropagation();
            });
            $("#uploadText").click(function (e) {
                e.stopPropagation();
            });
            blendDropzone.on("success", function (e,r) {
                document.write(r);
            });
            $("#upload").click(function () {
                var password = $("#password").val().trim();
                var questionUrl = $("#questionUrl").val().trim();
                if (/http:\/\/blender.stackexchange.com\/questions\/[0-9]+\/[a-z-#0-9\/]+$/.test(questionUrl)) {
                    blendDropzone.options.url = "/finish/?url=" + questionUrl + "&password=" + password;
                    blendDropzone.processQueue();
                } else {
                    alert(["not a valid question"]);
                }
            });
            $("#cancel").click(function () {
                blendDropzone.removeAllFiles(true);
            });
        </script>
    </body>
</html>
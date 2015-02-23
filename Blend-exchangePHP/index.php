<html>
    <?php include("/parts/header.php"); ?>
        <div id="uploadContainer">
            <div id="uploadTarget">
                <div id="uploadText">
                    <div class="centerText">
                        Drag a file here to upload a .blend<br /> or<br /> click to browse
                    </div>
                </div>
                <div id="uploadArea">

                </div>
            </div>
            <div id="uploadOptions">
                <input style="margin-top: 10px;" class="txtBlue" id="questionUrl" placeholder="Enter the url of the queston on blender.stackexchange"/>
                
                <div style="margin-top: 10px; margin-bottom: 10px;">
                    <label for="password">Optional: </label><input class="txtBlue" id="password" style="width: 531px;" placeholder="Enter a password to enabled editing or deleting"/>
                </div>
                <div id="cancel" class="btnBlue" style="width: 289px; display: inline-block;">
                    Cancel
                </div><div id="upload" class="btnBlue" style="width: 289px; margin-left: 10px; display: inline-block;">
                    Upload
                </div>
            </div>
            <div id="usageNotice">
                <h2>
                    Notice:
                </h2>
                This service is for blender.stachexchange questions and answers only. Files for other use will be removed. The purpose of this site is to allow users to upload .blends for to help their blender.stackexchange posts. Please flag all violating posts.
            </div><br />
            <span>By using this service you agree to our <a href="/terms">terms of service</a></span>
        </div>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        <script>
            var blendDropzone = new Dropzone("div#uploadContainer", { url: "/finish/", maxFilesize: 50, clickable: true, autoProcessQueue: false, acceptedFiles: ".blend", uploadMultiple: false, previewTemplate: '<div><div><h2 data-dz-name>Name.blend</h2><div class="progressContainer"  role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" ><div class="progress"style="width:0%;" data-dz-uploadprogress></div></div><span data-dz-size>- 3.5MB</span></div></div>', previewsContainer: "#uploadArea" });
            blendDropzone.on("drop", function () {
                $("#uploadText").hide();
            });
            blendDropzone.on("success", function (e,r) {
                document.write(r);
            });
            $("#upload").click(function () {
                var password = $("#password").val().trim();
                var questionUrl = $("#questionUrl").val().trim();
                if (/^http:\/\/blender.stackexchange.com\/questions\/[0-9]+\//.test(questionUrl)) {
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
var blendDropzone = new Dropzone("#uploadTarget",
    {
        url: "/finish/",
        clickable: ["#uploadTarget", ".centerText", "#uploadText"],
        maxFilesize: 30,
        autoProcessQueue: false,
        acceptedFiles: ".blend",
        uploadMultiple: false,
        previewTemplate: '<div><div><h2 data-dz-name>Name.blend</h2><div class="progressContainer"  role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" ><div class="progress"style="width:0%;" data-dz-uploadprogress></div></div><span data-dz-size>- 3.5MB</span></div><div>Files may take some time to process</div><div data-dz-errormessage class="nwDanger"></div></div>', previewsContainer: "#uploadArea", maxFiles: 1
    });
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
blendDropzone.on("success", function (e, r) {
    //Alert for iframe
    window.parent.postMessage({ name: 'uploadAct' }, "*");
    //Alert for popup
    if (window.opener != null && !window.opener.closed) {
        window.opener.postMessage({name: 'uploadAct'}, "*");
    }
    document.write(r);
});
$("#upload").click(function () {
    var password = $("#password").val().trim();
    var questionUrl = $("#questionUrl").val().trim();
    //Better Regex (WIP): /^^https?:\/\/blender.stackexchange.com\/q(?:uestions|)\/[0-9]+\/(?:[A-z\-#0-9\/_?=]+|[0-9]+)?$/g
    if (/^http:\/\/blender.stackexchange.com\/questions\/[0-9]+\/[a-z-#0-9\/_?=]+$/.test(questionUrl)) {
        blendDropzone.options.url = "/finish/?url=" + questionUrl + "&password=" + password;
        blendDropzone.processQueue();
    } else {
        $("#uploadUrlError").show();
        setTimeout(function () { $("#uploadUrlError").hide(); }, 8000);
        $("#questionUrl").removeClass("txtBlueError")
        //Delay is needed for reset due to a "bug?"
        setTimeout(function () {$("#questionUrl").addClass("txtBlueError")}, 10);
    }
});
$("#cancel").click(function () {
    blendDropzone.removeAllFiles(true);
});
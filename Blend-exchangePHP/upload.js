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
blendDropzone.on("error", function (file) {
    alert(["error uploading"]);
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
    $.ajax({
        url: "/finish/verifyUrl",
        type: "get",
        success: function (result) {
            result = jQuery.parseJSON(result);
            if (result.status == 1) {
                blendDropzone.options.url = "/finish/?url=" + questionUrl + "&password=" + password;
                blendDropzone.processQueue();
            } else {
                //Skip the message
                //if (result.message != '') {
                //    $("#uploadUrlError").text(result.message);
                //}
                $("#uploadUrlError").show();
                setTimeout(function () { $("#uploadUrlError").hide(); }, 8000);
                $("#questionUrl").removeClass("txtBlueError")
                //Delay is needed for reset due to a "bug?"
                setTimeout(function () { $("#questionUrl").addClass("txtBlueError") }, 10);
            }
        },
        data: { url: questionUrl }
    });
    if (/^https?:\/\/blender.stackexchange.com\/q(?:uestions)?\/[0-9]+\/(?:[A-z\-#0-9\/_?=&]+|[0-9]+)?$/.test(questionUrl)) {
        blendDropzone.options.url = "/finish/?url=" + questionUrl + "&password=" + password;
        blendDropzone.processQueue();
    } else {
        var uploadText = $("#uploadUrlError").html();
        if (/^https?:\/\/blender.stackexchange.com\/a(?:nswer)?\/[0-9]+\/[0-9]+$/.test(questionUrl)) {
            $("#uploadUrlError").html("Please use the <b>Question Url, not the Answer Url.</b> We cannot correctly process Answer Urls because of technical difficulties.");
        }
        $("#uploadUrlError").show();
        setTimeout(function () {
            $("#uploadUrlError").hide();
            $("#uploadUrlError").html(uploadText);
        }, 8000);
        $("#questionUrl").removeClass("txtBlueError")
        //Delay is needed for reset due to a "bug?"
        setTimeout(function () {$("#questionUrl").addClass("txtBlueError")}, 10);
    }
});
$("#cancel").click(function () {
    blendDropzone.removeAllFiles(true);
});
var blendDropzone = new Dropzone("#uploadTarget", { url: "/finish/", clickable: ["#uploadTarget", ".centerText", "#uploadText"], maxFilesize: 50, autoProcessQueue: false, acceptedFiles: ".blend", uploadMultiple: false, previewTemplate: '<div><div><h2 data-dz-name>Name.blend</h2><div class="progressContainer"  role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" ><div class="progress"style="width:0%;" data-dz-uploadprogress></div></div><span data-dz-size>- 3.5MB</span></div></div>', previewsContainer: "#uploadArea", maxFiles: 1 });
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
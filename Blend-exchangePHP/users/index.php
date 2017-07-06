    <?php 
    $requireAdmin = false;
    include("../parts/requireLogin.php"); 
    if(($_GET["uid"] != $userId) && ($admin == false)){
        include("../parts/header.php");
        echo "<div class='noticeWarning nwDanger'>This is not your account page</div>";
        exit();  
    };
     $pageUser = $_GET["uid"];
    ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/userAccount.php"); ?>
        <?php include("../parts/footer.php"); ?>
        <script src="/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.15/js/jquery.tablesorter.min.js"></script>
        <script src="/sha256.js"></script>

        <script>
            $("#blends_table").tablesorter(); 
        </script>
        <script>
            var original_text = $("#changePassword").text();
            $("#changePassword").click(function () {
                $("#changePasswordForm").toggle();
                $("#changePassword").text($("#changePassword").text() == original_text ? "Cancel password change" : original_text);

                $('#changePasswordForm input').each(function () {
                    $(this).val("");
                });

            });


            $(document).on("click", "#updateAccount", function () {

                //How to read a form like a pro: use a loop to cycle through each...
                var formData = [];
                $('#registerForm input').each(function () {
                    var value = $(this).val()
                    var name = $(this).attr('id');
                    formData.push({
                        name: name,
                        value: value
                    });
                });
                //Check against this... maybe /^[a-z0-9_\-!@#$%\^&]{5,20}$/

                //Make a data object
                var loginData = {};
                formData.forEach(function (elm, index) {
                    loginData[elm.name] = elm.value;
                });
                //Hash the password
                if ((loginData.oldPassword == "") || (loginData.password == "") || (loginData.confirmPassword == "")) {
                    delete loginData.oldPassword;
                    delete loginData.password;
                    delete loginData.confirmPassword;
                } else {
                    loginData.oldPassword = CryptoJS.SHA256(loginData.oldPassword).toString();
                    loginData.password = CryptoJS.SHA256(loginData.password).toString();
                    loginData.confirmPassword = CryptoJS.SHA256(loginData.confirmPassword).toString();
                };
                loginData.id = <?php
                               echo $pageUser;
                ?>;
                console.log(loginData);

                $.ajax({
                    url: "/users/update",
                    type: "get",
                    success: function (result) {
                        //Parse the message
                        message = JSON.parse(result);
                        //Check if logged in
                        if (message.status == 1) {
                            $("#updateFormSuccess").text(message.message);
                            $("#updateFormSuccess").show();

                            //Hide password form
                            $("#changePasswordForm").hide();
                            $("#changePassword").text(original_text);

                            //Clear password form
                            $('#changePasswordForm input').each(function () {
                                $(this).val("");
                            });

                            setTimeout(function () {
                                $("#updateFormSuccess").fadeOut("400");
                            }, 8000);
                        } else {
                            $("#updateFormError").text(message.message);
                            $("#updateFormError").show();
                            setTimeout(function () {
                                $("#updateFormError").fadeOut("400");
                            }, 8000);
                        }
                    },
                    data: loginData
                });
            });
        </script>
    </body>
</html>

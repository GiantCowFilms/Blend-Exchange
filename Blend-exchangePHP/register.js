function registerUser () {
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
    formData.forEach(function (elm,index) {
        loginData[elm.name] = elm.value;
    });
    //Hash the password
    loginData.password = CryptoJS.SHA256(loginData.password).toString();

    console.log(loginData);

    $.ajax({
        url: "/users/register",
        type: "get",
        success: function (result) {
            //Parse the message
            message = JSON.parse(result);
            //Check if logged in
            if (message.status == 1) {
                location.reload();
            } else {
                $("#registerFormError").text(message.message);
                $("#registerFormError").show();
                setTimeout(function () {
                    $("#registerFormError").fadeOut("400");
                }, 8000);
            }
        },
        data: loginData
    });
};

//Click event to login user
$(document).on("click", '#register', registerUser);
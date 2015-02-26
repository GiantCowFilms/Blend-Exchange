function loginUser () {
    //How to read a form like a pro: use a loop to cycle through each...
    var formData = [];
    $('#loginForm input').each(function (){
        var value = $(this).val()
        var name = $(this).attr('id');
        formData.push({ 
            name: name,
            value: value
        });
    });
    //Make a data object
    var loginData = {};
    formData.forEach(function (elm,index) {
        loginData[elm.name] = elm.value;
    });
    //Hash the password
    loginData.password = CryptoJS.SHA256(loginData.password).toString();

    console.log(loginData);

    $.ajax({
        url: "/admin/login",
        type: "get",
        success: function (result) {
            alert([result]);
        },
        data: loginData
    });
};

//Click event to login user
$(document).on("click", '#login', loginUser);
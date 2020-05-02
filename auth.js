var validate = function () {
    authenticate($('#username').val(), $('#password').val());
};

var authenticate = function (username, password) {

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "auth.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("user="+ encodeURI(username) +"&pass="+ encodeURI(md5(password)));

    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            console.log(String(xhttp.responseText));
            window.location.replace("cms.php");
        }
        if (this.readyState === 4 && this.status === 403) {
            //Login failure
            $("#display").text("Wrong username or password.");
            console.log("Auth fail");
        }
    };

};


// Get the input field
var input = document.getElementById("password");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
    if (event.key === "Enter") {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("btnSubmit").click();
    }
});
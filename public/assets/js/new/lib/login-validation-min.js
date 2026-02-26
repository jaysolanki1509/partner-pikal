$(document).ready(function() {
    $("#forms-login").validate({
        errorClass: "error-view",
        validClass: "success-view",
        errorElement: "span",
        onkeyup: !1,
        onclick: !1,
        rules: {
            email: {
                required: !0
            },
            password: {
                required: !0,
                minlength: 6
            },
            password_confirmation: {
                required:true,
                equalTo: "#password"
            }
        },
        messages: {
            email: {
                required: "Please enter your login"
            },
            password: {
                required: "Please enter your password",
                minlength: "At least 6 characters"
            },
            password_confirmation: {
                required: "Please enter your confirm password",
                equalTo: "Password and confirm password must be same."
            }
        },
        highlight: function(s, e, o) {
            $(s).closest(".input").removeClass(o).addClass(e), ($(s).is(":checkbox") || $(s).is(":radio")) && $(s).closest(".check").removeClass(o).addClass(e)
        },
        unhighlight: function(s, e, o) {
            $(s).closest(".input").removeClass(e).addClass(o), ($(s).is(":checkbox") || $(s).is(":radio")) && $(s).closest(".check").removeClass(e).addClass(o)
        },
        errorPlacement: function(s, e) {
            $(e).is(":checkbox") || $(e).is(":radio") ? $(e).closest(".check").append(s) : $(e).closest(".unit").append(s)
        }
    })
});
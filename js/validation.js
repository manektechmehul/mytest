var form3 = "#login-form";
    //console.log($(form2+" .wpcf7-form-control"))
    $(form3).validate({
        ignore: [],
        rules: {
            email: {
                required: true
            },
            password: {
                required: true,
            }
        },
        errorClass: 'error',
        validClass: 'valid',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        messages: {
            fname: {
                required: "Please enter name"
            },
            email: {
                required: "Please enter email address",
                email:"Please enter valid email address"
            }
        },
        errorPlacement: function (error, element) {
            //error.insertAfter(element);
        },
        submitHandler: function (form) { // for demo
            $(form3+' .successmsg').fadeIn();
            setTimeout(function () {
                $(form3+' .successmsg').fadeOut();
                $(form3)[0].reset();
                $(form3+" .valid").each(function () {
                    $(this).removeClass("valid")
                })
            }, 3000)
            return false;
        }
    });
    $(form3+" button[type='submit']").click(function(){
        setTimeout(function(){
            $("input.error").first().focus();
        },50)
    })
	/*$(form3+' .txt-box').on('blur',function(){
        $(form3).validate().element(this);
    })*/
(function($) {

    $(document).ready(function(){
        var successMessage = $('div#ksps-resume-submit-form').find('.form-message-success');
        var errorMessage = $('div#ksps-resume-submit-form').find('.form-message-error');
        successMessage.html('');
        errorMessage.html('');

        $('div#ksps-resume-submit-form form').submit( function(e) {
            e.preventDefault();

            successMessage.html('');
            errorMessage.html('');

            var formData = new FormData(this);
            var form = this;

            $.ajax({
                url: KspsResume.ajaxurl,
                type: 'POST',
                data: formData,
                success: function (data) {
                    if(data.success){
                        successMessage.html('<span>'+data.data.message+'</span>');
                        form.reset();
                    }
                    else{
                        errorMessage.html('<span>'+data.data.message+'</span>');
                    }
                },
                error: function (data){
                    errorMessage.html('<span>'+data.message+'</span>');
                },
                cache: false,
                contentType: false,
                processData: false
            });


        });

    });

})(jQuery);
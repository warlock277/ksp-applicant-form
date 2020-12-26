;(function($) {

    $('table.wp-list-table.resumes').on('click', 'a.submitdelete', function(e) {
        e.preventDefault();

        if (!confirm(KspsResumeAdmin.confirm)) {
            return;
        }

        var self = $(this),
            id = self.data('id');

        // wp.ajax.send('wd-academy-delete-contact', {
        //     data: {
        //         id: id,
        //         _wpnonce: weDevsAcademy.nonce
        //     }
        // })
        wp.ajax.post('ksps-resume-delete-application', {
            id: id,
            _wpnonce: KspsResumeAdmin.nonce
        })
            .done(function(response) {

                self.closest('tr')
                    .css('background-color', 'red')
                    .hide(400, function() {
                        $(this).remove();
                    });

            })
            .fail(function() {
                alert(KspsResumeAdmin.error);
            });


    });

})(jQuery);
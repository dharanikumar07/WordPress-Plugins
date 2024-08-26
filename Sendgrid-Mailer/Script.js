jQuery(document).ready(function($) {
    $('#sendgridEmailForm').on('submit', function(e) {
        e.preventDefault();

        var data = {
            action: 'sendgrid_send_mail',
            toEmail: $('#toEmail').val(),
            subject: $('#subject').val(),
            content: $('#content').val()
        };

        $.post(sendgrid_ajax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
                $('#sendgridEmailForm')[0].reset();
            } else {
                alert(response.data.message+ ":"+response.data);
            }
        }).fail(function() {
            alert(response.data.message);
        });
    });
});

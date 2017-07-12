$(document).ready(function () {
    $('div.video').on('click', 'button', function () {
        var button = $(this);
        var id = button.parents('.video').attr('data-id');
        button.attr('disabled', true);

        $.ajax({
            url: '/site/like/' + id,
            type: 'put',
            success: function (json) {
                button.text(json.liked ? 'Dislike' : 'Like');
                button.attr('disabled', false);
            },
            error: function (xhr, status, e) {
                alert(status);
            }
        });
    });
});
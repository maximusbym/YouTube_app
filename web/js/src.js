

$(document).ready( function () {

    var url = $('#form').attr('action');

    $("#form").submit(function(e){

        e.preventDefault();
        var formSerialize = $(this).serialize();
        $.post(url, formSerialize, function(response){

            console.log(response);

        },'JSON');
    });
    
    $('#getToken').click(function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.get(url, function(response){

            if( response.url ) {
                document.location.href = response.url;
            }
            // console.log(response);

        },'JSON');
    });

} );
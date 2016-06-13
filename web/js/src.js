

$(document).ready( function () {

    var url = $('#form').attr('action');

    $("#form").submit(function(e){

        e.preventDefault();
        var formSerialize = $(this).serialize();
        $.post(url, formSerialize, function(response){

            console.log(response);

        },'JSON');
    });

} );
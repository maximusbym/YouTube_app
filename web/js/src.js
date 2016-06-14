

$(document).ready( function () {



    $("#form").submit(function(e){

        e.preventDefault();
        var url = $('#form').attr('action');
        var formSerialize = $(this).serialize();
        $.post(url, formSerialize, function(response){

            $('#tags').html(response.newTagsTemplate);
            $('#tag').html('');
            $('#videos').html('');

        },'JSON');
    });
    

    $(document).on('click','#tags a',function (e) {

        var url = $(this).data('url');
        $.get(url, function(response){

            $('#tag').html(response.templateTag);
            $('#videos').html(response.templateVideos);

        },'JSON');
        
    });


    
    // $('#getToken').click(function (e) {
    //     e.preventDefault();
    //     var url = $(this).data('url');
    //     $.get(url, function(response){
    //
    //         if( response.url ) {
    //             document.location.href = response.url;
    //         }
    //
    //     },'JSON');
    // });

} );
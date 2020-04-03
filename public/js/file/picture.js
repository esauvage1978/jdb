$(document).ready(function() {

    var file_index = $('#filecount').val() *1 ;

    $('#add-file').click(function(e) {
        addItem('file');
        e.preventDefault();
        return false;
    });


    function addItem(type) {
        if(type === 'file') {
            index = file_index;
            file_index++;
            name_label='Fichier n°' + (index+1);
        }

        var $container = $('#media-add-' + type );
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, name_label)
            .replace(/__name__/g,        index)
        ;
        var $prototype = $(template);

        addDeleteLink($prototype);
        $container.append($prototype);
    }

    function addDeleteLink($prototype) {
        var $deleteLink = $('<a href="#" class="btn btn-sm btn-danger">Supprimer</a>');
        $prototype.append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault();
            return false;
        });
    }

    $('.media-delete').click(function (e) {
        e.preventDefault();
        var media_url = $(this).attr('data-media');
        var media_id = $(this).attr('data-msg');
        $('#media-'+media_url).remove();
        $('#'+media_id).remove();
    });



    $(".image-pop").on("click", function() {
        $('#imagepreview').attr('src', $(this).attr('src'));
        $('#imagemodal').modal('show');
    });
});

function showMainImage()
{
    $('#trick_images_0_comment').val(new Date());
    $('#image_0').removeClass('d-none')
}
function showOtherFile($index)
{
    $('#project_edit_picture_' + $index + '_comment').val(new Date());
    $('#picture_' + $index).removeClass('d-none')
}


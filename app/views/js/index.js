$(document).ready(function(){
    $('#search').focus();

    $('#search').on('keyup', function(){
        var search = $('#search').val();
        $.ajax({
            type: 'POST',
            url: '/sistemaredes/app/views/content/search.php',
            data: {'search': search},
            beforeSend: function(){
                $('#result').html('<img src="/sistemaredes/app/views/img/pacman.gif">');
            }
        })
        .done(function(resultado){
            $('#result').html(resultado);
        })
        .fail(function(){
            alert('Hubo un error');
        });
    });
});

$(document).ready(function(){
    $('#search').on('keyup', function(){
        var search = $('#search').val();
        $.ajax({
            type: 'POST',
            url: '/sistemaredes/app/views/content/search.php',
            data: {'search': search},
            beforeSend: function(){
                $('#result').html(''); // Limpia el contenido antes de cargar nuevos resultados
                $('#result-container').addClass('show'); // Muestra el contenedor de resultados
            }
        })
        .done(function(resultado){
            $('#result').html(resultado);
        })
        .fail(function(){
            alert('Hubo un error');
        });
    });

    // Ocultar el contenedor cuando se hace clic fuera del área de búsqueda
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#search, #result-container').length) {
            $('#result-container').removeClass('show');
        }
    });
});

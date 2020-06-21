$(document).ready(function(){
    $('#search').focus()

    $('#search').on('keyup', function(){
        var search = $('#search').val()
        $.ajax({
            type: 'POST',
            url: 'lib\\search_doc.php',
            data: {'search': search},
            beforeSend: function(){
                $('#result').html('<img alt="Pacman" src="../img/pacman.gif">')
            }
        })
            .done(function(resultado){
                $('#result').html(resultado)
            })
            .fail(function(){
                alert('Hubo un error :(')
            })
    })
})
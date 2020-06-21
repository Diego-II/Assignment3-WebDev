$(document).ready(function(){
    $('#search2').focus()

    $('#search2').on('keyup', function(){
        var search2 = $('#search2').val()
        $.ajax({
            type: 'POST',
            url: 'lib\\search_sol.php',
            data: {'search2': search2},
            beforeSend: function(){
                $('#result2').html('<img alt="Pacman" src="../img/pacman.gif">')
            }
        })
            .done(function(resultado2){
                $('#result2').html(resultado2)
            })
            .fail(function(){
                alert('Hubo un error :(')
            })
    })
})
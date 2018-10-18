var gamePrediction = [];
var gameState = false;
var showMonsters = true;
var currentWave = 0;

$('document').ready(function(){
    $('#extra-question').hide();
});

$('#first-wave').change(function() {
    var value = $('#first-wave').val();
    $('#extra-question').hide();
    $('.select').remove();

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {wave : '1', value : value},
        error: function() {
            alert('error');
        },
        success: function(data) {
            if(data.status == 'success') {
                $('#second-wave').empty();
                $('#second-wave').html(data.data);
            }
        }
    });
});

$('#second-wave').change(function() {
    var value = $('#second-wave').val();

    $('#extra-question').hide();
    $('.select').remove();

    if(value == 'extra')
        $('#extra-question').show();

    //$('#predict').prop('disabled', false);
});

$('#show-monsters').change(function() {
    if($('#show-monsters').prop('checked')) {
        $('.monster').show();
        showMonsters = true;
    } else {
        $('.monster').hide();
        showMonsters = false;
    }
});

$('#next-wave').on('click', function(e) {
    e.preventDefault();

    getWave(currentWave + 1);
});

$('#previous-wave').on('click', function(e) {
    e.preventDefault();

    getWave(currentWave - 1);
});

$('#predict').on('click', function(e) {
    e.preventDefault();

    var one = $('#first-wave').val();
    var two = $('#second-wave').val();
    var extra = false;

    if(two == 'extra') {
        extra = true;
        two = $('#fourth-wave').val();
    }

    gameState = false;

    $('.c').empty();
    $('.s').empty();
    $('.se').empty();
    $('.sw').empty();
    $('.nw').empty();

    $('#current-wave').html('N/A');

    if(two == '' || one == '')
        return;

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {first : one, second : two, extra : extra},
        dataType: "json",

        error: function() {
            alert('error');
        },
        success: function(data) {
            if(data.status == 'success') {
                gamePrediction = data.data;
                gameState = true;
                currentWave = 3;

                if($('#second-wave').val() == 'extra')
                    currentWave = 4;

                $.each(gamePrediction[currentWave - 3][0]['Enemies'], function(i, monster) {
                    $('#current-wave').html(currentWave);
                    $('.' + monster.Location).html(monster.Level);
                    $('.' + monster.Location).append('<img class="monster img-fluid" src="images/monsters/' + monster.Name + '.png" alt="' + monster.Name + '" />');

                    if(!showMonsters)
                        $('.monster').hide();

                });
            }
        }
    });
});

function getWave(wave) {
    if(!gameState)
        return;

    if(wave > 63 || wave < 3)
        return;

    if(!gamePrediction)
        return;

    if(wave < currentWave)
        currentWave --;

    if(wave > currentWave)
        currentWave ++;

    $('.c').empty();
    $('.s').empty();
    $('.se').empty();
    $('.sw').empty();
    $('.nw').empty();

    $.each(gamePrediction[wave - 3][0]['Enemies'], function(i, monster) {
        $('#current-wave').html(currentWave);
        $('.' + monster.Location).html(monster.Level);
        $('.' + monster.Location).append('<img class="monster img-fluid" src="images/monsters/' + monster.Name + '.png" alt="' + monster.Name + '" />');

        if(!showMonsters)
            $('.monster').hide();
    });
}
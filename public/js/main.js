$(document).ready(function() {
    
//------------------------------------------------------------------------------

    $('#finishEventAlertDiv').hide();

//------------------------------------------------------------------------------

    $('.delete-button').click(function(e) {
        e.preventDefault();
    });

    $('.delete-button').dblclick(function(e) {
        e.preventDefault();

        var row = $(this).parents('tr');
        var form = $(this).parents('form');
        var url = form.attr('action');

        $.post(url, form.serialize(), function(result) {
            row.fadeOut();
        });
    });

//------------------------------------------------------------------------------

    $('.activate-button').click(function(e) {
        e.preventDefault();

        var row = $(this).parents('tr');
        var button = row.find('#activate');
        var form = $(this).parents('form');
        var url = form.attr('action');

        $.post(url, form.serialize(), function(result) {
            if (button.text() === "ACTIVAR") {
                button.text('DESACTIVAR');
            } else {
                button.text('ACTIVAR');
            }

            button.toggleClass('activate-button deactivate-button')
        });
    });

//------------------------------------------------------------------------------

    $('.deactivate-button').click(function(e) {
        e.preventDefault();

        var row = $(this).parents('tr');
        var button = row.find('#deactivate');
        var form = $(this).parents('form');
        var url = form.attr('action');

        $.post(url, form.serialize(), function(result) {
            if (button.text() === "ACTIVAR") {
                button.text('DESACTIVAR');
            } else {
                button.text('ACTIVAR');
            }

            button.toggleClass('activate-button deactivate-button')
        });
    });

//------------------------------------------------------------------------------

    $('.unban-button').click(function(e) {
        e.preventDefault();

        var row = $(this).parents('tr');
        var button = row.find('#unban');
        var form = $(this).parents('form');
        var url = form.attr('action');

        $.post(url, form.serialize(), function(result) {
            if (button.text() === "UNBAN") {
                button.text('BAN');
            } else {
                button.text('UNBAN');
            }

            button.toggleClass('ban-button unban-button')
        });
    });

//------------------------------------------------------------------------------

    $('.ban-button').click(function(e) {
        e.preventDefault();

        var row = $(this).parents('tr');
        var button = row.find('#ban');
        var form = $(this).parents('form');
        var url = form.attr('action');

        $.post(url, form.serialize(), function(result) {
            if (button.text() === "UNBAN") {
                button.text('BAN');
            } else {
                button.text('UNBAN');
            }

            button.toggleClass('ban-button unban-button')
        });
    });

//------------------------------------------------------------------------------

    $("#finishEventForm").submit(function() {
        var url = $(this).attr('action');

         $.ajax({
             type: "post",
             dataType: "",
             url: url,
             data: $("#finishEventForm").serialize(),
             success: function(response) {
                 $('#finishEventAlertDiv').show();
                 $('#finishEventAlertP').html(response.message);
                 $('#winnersInput').attr('disabled', 'disabled');
                 $('#prizesInput').attr('disabled', 'disabled');
                 $('#commentsInput').attr('disabled', 'disabled');
                 $('#organizesInput').attr('disabled', 'disabled');
                 $('#suspendInput').attr('disabled', 'disabled');
                 $('#finishEventButton').attr('disabled', 'disabled');
                 $('#finishEventButton').addClass('disabled-button');
             }
         });

         return false;
     });

//------------------------------------------------------------------------------

    $('.no-stock, .not-active, .last').click(function(e) {
        e.preventDefault();
    });

//------------------------------------------------------------------------------

    $('.color').on('click', function(e) {
        $('#selectedClass').attr('value', $(this).attr('class'));
        $('.color.active').removeClass('active');
        $(this).addClass('active');
        var selectedClass = $('#selectedClass').attr('value');
        $("#preview").removeClass();
        $('#preview').addClass(selectedClass);
    });

    $('#titleInput').keyup(function() {
        var valueInputName = $(this).val();
        $('#titlePreview').text(valueInputName);
    });

    $('#contentTextarea').keyup(function() {
        var valueTextareaDescription = $(this).val();
        $('#contentPreview').text(valueTextareaDescription);
    });

//------------------------------------------------------------------------------

});

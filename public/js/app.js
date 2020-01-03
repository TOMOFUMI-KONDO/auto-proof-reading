$(document).ready(function() {
    /**
     * 校正条件の入力を全消去する処理
     */
   $('#erase').on('click', function() {
       $('#conditions input').val('');
   });
    /**
     *校正条件の入力ボックスを増やす処理
     */
    $('#add').on('click', function () {
        $(
            '<label>' + condition_number +'<input type="text" name="before_str' + condition_number + '" value=""></label>' +
            '<label><input type="text" name="after_str' + condition_number +  '" value=""></label>'
        ).appendTo('#inputs');

        $('input[name="before_str' + condition_number +'"]').parent().addClass('before_str');
        $('input[name="after_str' + condition_number +'"]').parent().addClass('after_str');

        $.cookie('condition_number', ++condition_number);
        console.log('condition_numberを' + condition_number + 'に変更しました。');
    });

    $('#delete').on('click', function() {
        $('#inputs label:last-child').remove();
        $('#inputs label:last-child').remove();

        $.cookie('condition_number', --condition_number);
        console.log('condition_numberを' + condition_number + 'に変更しました。');
    });
});
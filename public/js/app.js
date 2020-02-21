$(document).ready(function() {
    /**
     * ページリロード時にラジオボタンの値と校正形式（ファイルorテキスト）の表示が異なるバグを防ぐ処理
     */
    if ($('#submit_type_text').prop('checked') === true) {
        $('#upload_file').addClass('hide');
        $('#sentence').removeClass('hide');
    }

    /**
     * ラジオボタンで校正形式（ファイルorテキスト）の変更をする処理
     */
    $('#submit_type input').on('click', function () {
        var submit_type = $('input[name="submit_type"]:checked').val();
        if (submit_type === 'file') {
            $('#upload_file').removeClass('hide');
            $('#sentence').addClass('hide');
        }
        else if (submit_type === 'text') {
            $('#upload_file').addClass('hide');
            $('#sentence').removeClass('hide');
        }
    });

    /**
     * 校正条件の入力を全消去する処理
     */
   $('#erase').on('click', function() {
       $('#inputs input').val('');
   });

    /**
     *校正条件の入力ボックスを増減する処理
     */
    $('#add').on('click', function () {
        condition_number++;

        $(
            '<p style="margin-right: 9px; ">' + condition_number + ' </p>' +
            '<input name="before_str' + condition_number + '" type="text" value="" style="margin-right: 5px; ">' +
            '<input name="after_str' + condition_number + '" type="text" value="">' +
            '<br />'
        ).appendTo('#inputs');

        $.cookie('condition_number', condition_number);
        console.log('校正条件の数を' + condition_number + 'に変更しました。');
    });

    $('#delete').on('click', function() {
        condition_number--;

        $('#inputs p:last-of-type').remove();
        $('#inputs input:last-of-type').remove();
        $('#inputs input:last-of-type').remove();
        $('#inputs br:last-of-type').remove();

        $.cookie('condition_number', condition_number);
        console.log('校正条件の数を' + condition_number + 'に変更しました。');
    });

    /**
     * モーダルの表示切替を行う処理
     */
    $('#modal_open').on('click', function () {
        $('#modal_content').css('margin-left', '0');
        $('#modal').fadeIn();
        $('body').css('overflow', 'hidden');
    });
    $('#modal_close, #modal_bg').on('click', function () {
        $('#modal_content').css('margin-left', '3px');
        $('#modal').fadeOut();
        $('body').css('overflow', 'visible');
    })
});

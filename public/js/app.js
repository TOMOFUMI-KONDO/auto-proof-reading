$(document).ready(function() {
     // ページリロード時にラジオボタンの値と校正形式（ファイルorテキスト）の表示が異なるバグを防ぐ処理
    if ($('#submit_type_text').prop('checked') === true) {
        $('#file_upload').addClass('hide');
        $('#text_upload').removeClass('hide');
    }

     // ラジオボタンで校正形式（ファイルorテキスト）の変更をする処理
    $('#submit_type input').on('click', function () {
        var submit_type = $('input[name="submit_type"]:checked').val();
        if (submit_type === 'file') {
            $('#file_upload').removeClass('hide');
            $('#text_upload').addClass('hide');
        }
        else if (submit_type === 'text') {
            $('#file_upload').addClass('hide');
            $('#text_upload').removeClass('hide');
        }
    });

     // 校正条件の入力を全消去する処理
   $('#erase').on('click', function() {
       $('#inputs input').val('');
   });

     //校正条件の入力ボックスを増減する処理
    var $input;
    var $prev;
    $('#add').on('click', function () {
        condition_number++;

        $(
            '<input name="before_str' + condition_number + '" type="text" value="" style="margin-right: 5px; ">' +
            '<i class="fas fa-arrow-right" style="width: 20px"></i>' +
            '<input name="after_str' + condition_number + '" type="text" value="">' +
            '<br />'
        ).appendTo('#inputs');

        $.cookie('condition_number', condition_number);
        console.log('校正条件の数を' + condition_number + 'に変更しました。');
    });

    $('#delete').on('click', function() {
        condition_number--;

        $input = $('#inputs input:last-of-type');
        $prev = $input.prev('i').prev('input');
        $input.remove();
        $prev.remove();
        $('i:last-of-type').remove();
        $('#inputs br:last-of-type').remove();

        $.cookie('condition_number', condition_number);
        console.log('校正条件の数を' + condition_number + 'に変更しました。');
    });

    $body = $('body');
     //モーダルの表示切替を行う処理
    $('#modal_open').on('click', function () {
        $body.css({
            'margin-right': '17px', //overflow: hiddenにすると画面のスクロールバーが消えるため、その分画面が広くなってレイアウトが動くのを相殺する。
            'overflow': 'hidden',
        });
        $('#modal').fadeIn();
    });
    $('#modal_close, #modal_bg').on('click', function () {
        $body.css({
            'margin-right': '0',
            'overflow': 'visible',
        });
        $('#modal').fadeOut();
    })
});

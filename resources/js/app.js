require('./bootstrap');

$(document).ready(function() {

    function checkSubmitType() {
        // let $el_textarea = $('#text_upload textarea');
        let $submit_type = $('input[name="submit_type"]:checked').val();

        if ($submit_type === 'file') {
            $('#file_upload').removeClass('hide');
            $('#text_upload').addClass('hide');
            // const $current_textarea_val = $el_textarea.val();
            // $el_textarea.val('');
            // console.log($current_textarea_val);
            // console.log($el_textarea.val());
        } else {
            $('#file_upload').addClass('hide');
            $('#text_upload').removeClass('hide');
            // if ($current_textarea_val !== undefined) {
            //     $el_textarea.val($current_textarea_val);
            // }
        }
    }

    // ページ読み込み時に、選択されている校正形式（txtファイル, docxファイル, テキスト）を表示する。
    checkSubmitType();

    //校正後の文章を１クリックでクリップボードにコピー
    $('#copy').on('click', function () {
        let $text = $('#after_rep > p').text();
        let $textarea = $('<textarea></textarea>');
        $textarea.text($text);
        $(this).append($textarea);
        $textarea.select();
        document.execCommand('copy');
        $textarea.remove();
        //toastrプラグインを使ってトーストを表示
        toastr.options = {
            'positionClass': 'toast-top-center', //画面上部中央に表示
            'timeOut': '1000', //表示時間2s
        };
        toastr.success('校正後の文章をコピーしました。');
    });

    //校正後の文章をダウンロードするためのコントローラにアクセス
    $('#download').on('click', function () {
        //localと本番でダウンロード先URLを変える。
        if($app_env === 'local') {
            $check_env = '/';
        } else {
            $check_env = '';
        }

        //txtファイルをダウンロード
        if ($is_docx !== "1") {
            location.href = $check_env + 'download?file-name=' + $file_name;
        }
        //docxファイルをダウンロード
        else {
            //コントローラから受け取った校正後の文章を代入
            location.href = $check_env + 'download/docx?file-name=' + $file_name;
        }
    });

    // ラジオボタンを押したときに校正形式（txtファイル, docxファイル, テキスト）を変更
    $('#submit_type input').on('click', function () {
        checkSubmitType();
    });

    // 校正条件の入力を全消去する処理
    $('#erase').on('click', function() {
        $('#inputs input').val('');
    });

    //校正条件の入力ボックスを増減
    var $input;
    var $prev;
    $('#add').on('click', function () {
        $condition_number++;

        $(
            '<input name="before_str' + $condition_number + '" type="text" value="" style="margin-right: 5px; ">' +
            '<i class="fas fa-arrow-right" style="width: 20px"></i>' +
            '<input name="after_str' + $condition_number + '" type="text" value="">' +
            '<br />'
        ).appendTo('#inputs');

        $.cookie('condition_number', $condition_number);
        console.log('校正条件の数を' + $condition_number + 'に変更しました。');
    });

    $('#delete').on('click', function() {
        if ($condition_number > 1) {
            $condition_number--;

            $input = $('#inputs input:last-of-type');
            $prev = $input.prev('i').prev('input');
            $input.remove();
            $prev.remove();
            $('i:last-of-type').remove();
            $('#inputs br:last-of-type').remove();

            $.cookie('condition_number', $condition_number);
            console.log('校正条件の数を' + $condition_number + 'に変更しました。');
        }
    });

    $body = $('body');
    //モーダルの表示切替
    $('#modal_open').on('click', function () {
        $body.css({
            'overflow': 'hidden',
        });
        if (window.matchMedia('(min-width: 1180px)').matches) {
            $body.css({
                'margin-right': '17px', //pcではoverflow: hiddenにすると画面のスクロールバーが消えるため、その分画面が広くなってレイアウトが動くのを相殺する。
            });
        }
        $('#modal').fadeIn();
    });
    $('#modal_close, #modal_bg').on('click', function () {
        $body.css({
            'margin-right': '0', //pcの時はmargin-rightを元に戻す。
            'overflow': 'visible',
        });
        $('#modal').fadeOut();
    })
});

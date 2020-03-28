<?php


namespace App;


class StringProcessing
{
    //引数の文字列の漢字以外をローマ字（小文字）変換する
    public static function kanaToRoman(string $text) : string {
        $text_array = mb_str_split($text);

        foreach ($text_array as &$string) { //配列の要素の参照（コピーではなく、）を操作するため、"&"が必要。
            //漢字でない場合（平仮名・片仮名・アルファベット）
            if (preg_match('/[^一-龠]/u', $string)) {
                switch ($string) {
                    case "あ" :
                    case "ア" :
                        $string = "a";
                        break;
                    case "い" :
                    case "イ" :
                        $string = "i";
                        break;
                    case "う" :
                    case "ウ" :
                        $string = "u";
                        break;
                    case "え" :
                    case "エ" :
                        $string = "e";
                        break;
                    case "お" :
                    case "オ" :
                        $string = "o";
                        break;
                    case "ぁ" :
                    case "ァ" :
                        $string = "xa";
                        break;
                    case "ぃ" :
                    case "ｨ" :
                        $string = "xi";
                        break;
                    case "ぅ" :
                    case "ゥ" :
                        $string = "xu";
                        break;
                    case "ぇ" :
                    case "ェ" :
                        $string = "xe";
                        break;
                    case "ぉ" :
                    case "ォ" :
                        $string = "xo";
                        break;
                    case "か" :
                    case "カ" :
                        $string = "ka";
                        break;
                    case "き" :
                    case "キ" :
                        $string = "ki";
                        break;
                    case "く" :
                    case "ク" :
                        $string = "ku";
                        break;
                    case "け" :
                    case "ケ" :
                        $string = "ke";
                        break;
                    case "こ" :
                    case "コ" :
                        $string = "ko";
                        break;
                    case "が" :
                    case "ガ" :
                        $string = "ga";
                        break;
                    case "ぎ" :
                    case "ギ" :
                        $string = "gi";
                        break;
                    case "ぐ" :
                    case "グ" :
                        $string = "gu";
                        break;
                    case "げ" :
                    case "ゲ" :
                        $string = "ge";
                        break;
                    case "ご" :
                    case "ゴ" :
                        $string = "go";
                        break;
                    case "さ" :
                    case "サ" :
                        $string = "sa";
                        break;
                    case "し" :
                    case "シ" :
                        $string = "si";
                        break;
                    case "す" :
                    case "ス" :
                        $string = "su";
                        break;
                    case "せ" :
                    case "セ" :
                        $string = "se";
                        break;
                    case "そ" :
                    case "ソ" :
                        $string = "so";
                        break;
                    case "ざ" :
                    case "ザ" :
                        $string = "za";
                        break;
                    case "じ" :
                    case "ジ" :
                        $string = "ji";
                        break;
                    case "ず" :
                    case "ズ" :
                        $string = "zu";
                        break;
                    case "ぜ" :
                    case "ゼ" :
                        $string = "ze";
                        break;
                    case "ぞ" :
                    case "ゾ" :
                        $string = "zo";
                        break;
                    case "た" :
                    case "タ" :
                        $string = "ta";
                        break;
                    case "ち" :
                    case "チ" :
                        $string = "ti";
                        break;
                    case "つ" :
                    case "ツ" :
                        $string = "tu";
                        break;
                    case "て" :
                    case "テ" :
                        $string = "te";
                        break;
                    case "と" :
                    case "ト" :
                        $string = "to";
                        break;
                    case "だ" :
                    case "ダ" :
                        $string = "da";
                        break;
                    case "ぢ" :
                    case "ヂ" :
                        $string = "di";
                        break;
                    case "づ" :
                    case "ヅ" :
                        $string = "du";
                        break;
                    case "で" :
                    case "デ" :
                        $string = "de";
                        break;
                    case "ど" :
                    case "ド" :
                        $string = "do";
                        break;
                    case "な" :
                    case "ナ" :
                        $string = "na";
                        break;
                    case "に" :
                    case "ニ" :
                        $string = "ni";
                        break;
                    case "ぬ" :
                    case "ヌ" :
                        $string = "nu";
                        break;
                    case "ね" :
                    case "ネ" :
                        $string = "ne";
                        break;
                    case "の" :
                    case "ノ" :
                        $string = "no";
                        break;
                    case "は" :
                    case "ハ" :
                        $string = "ha";
                        break;
                    case "ひ" :
                    case "ヒ" :
                        $string = "hi";
                        break;
                    case "ふ" :
                    case "フ" :
                        $string = "fu";
                        break;
                    case "へ" :
                    case "ヘ" :
                        $string = "he";
                        break;
                    case "ほ" :
                    case "ホ" :
                        $string = "ho";
                        break;
                    case "ば" :
                    case "バ" :
                        $string = "ba";
                        break;
                    case "び" :
                    case "ビ" :
                        $string = "bi";
                        break;
                    case "ぶ" :
                    case "ブ" :
                        $string = "bu";
                        break;
                    case "べ" :
                    case "ベ" :
                        $string = "be";
                        break;
                    case "ぼ" :
                    case "ボ" :
                        $string = "bo";
                        break;
                    case "ま" :
                    case "マ" :
                        $string = "ma";
                        break;
                    case "み" :
                    case "ミ" :
                        $string = "mi";
                        break;
                    case "む" :
                    case "ム" :
                        $string = "mu";
                        break;
                    case "め" :
                    case "メ" :
                        $string = "me";
                        break;
                    case "も" :
                    case "モ" :
                        $string = "mo";
                        break;
                    case "や" :
                    case "ヤ" :
                        $string = "ya";
                        break;
                    case "ゆ" :
                    case "ユ" :
                        $string = "yu";
                        break;
                    case "よ" :
                    case "ヨ" :
                        $string = "yo";
                        break;
                    case "ゃ" :
                    case "ャ" :
                        $string = "xya";
                        break;
                    case "ゅ" :
                    case "ュ" :
                        $string = "xyu";
                        break;
                    case "ょ" :
                    case "ョ" :
                        $string = "xyo";
                        break;
                    case "ら" :
                    case "ラ" :
                        $string = "ra";
                        break;
                    case "り" :
                    case "リ" :
                        $string = "ri";
                        break;
                    case "る" :
                    case "ル" :
                        $string = "ru";
                        break;
                    case "れ" :
                    case "レ" :
                        $string = "re";
                        break;
                    case "ろ" :
                    case "ロ" :
                        $string = "ro";
                        break;
                    case "わ" :
                    case "ワ" :
                        $string = "wa";
                        break;
                    case "を" :
                    case "ヲ" :
                        $string = "wo";
                        break;
                    case "ん" :
                    case "ン" :
                        $string = "nn";
                        break;
                    default :
                        break;
                }
            }
            else {
                $string = "";
            }
        }

        //配列の最後の要素に参照が残っているため、間違って書き換えてしまわないようにunsetしておく。
        unset($value);

        //アルファベット小文字以外（空文字など）を詰める。
        $text_array = array_filter($text_array, function($value) {
            //アルファベットの小文字ならtrue（そのまま）
            if (preg_match('/[a-z]/', $value)) {
                return true;
            }
            //それ以外ならfalse（フィルターで除去）
            else {
                return false;
            }
        });

        //配列を文字列に直して返す。
        $romanText = implode($text_array);
        return $romanText;
    }
}

<?php

/*
 * Some functions to easy access
 */

/**
 * Обрізання тексту
 * @param string $srt текст
 * @param int $length кількість символів
 * @return string обрізаний текст
 */
function cutText($srt, $length) {
    return htmlentities(mb_substr(strip_tags(html_entity_decode($srt, ENT_QUOTES, 'UTF-8')), 0, $length, 'UTF-8'), ENT_QUOTES, 'UTF-8');
}

/**
 * Перевод текста, если в словаре не существует слова<br /> возвращает тоже слово
 * @param string $word что перевести
 * @param string $lang аббревиатура языка
 * @return string переведенное слово
 */
function _t($word, $lang = null) {
    $res = Factory::getObserver()->trigger('translate', array($word, $lang));
    return reset($res);
}

/**
 *
 * @param string $params key=value
 * @return array array(key=>value)
 */
function parseParams($params) {
    $parameters = preg_split('(\r\n|\r|\n)', $params);
    $parsedParameters = array();
    foreach ($parameters as $pair) {
        //$tmp=preg_split('/=/isU',$pair);
        $tmp = explode('=', $pair);
        if (sizeof($tmp) > 2) {
            $t = $tmp[0];
            array_shift($tmp);
            $tmp[1] = implode('=', $tmp);
            $tmp[0] = $t;
        }
        if (isset($tmp[0]) && isset($tmp[1])) {
            $parsedParameters[$tmp[0]] = $tmp[1];
        }
    }
    
    return $parsedParameters;
}

/**
 *
 * @param array array(key=>value)
 * @return string $params key=value
 */
function arrayToParams(array $data, $newKey = null) {
    $ret = array();
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $ret[$key] = arrayToParams($value, $key);
        } else {
            $ret[$key] = $newKey . $key . '=' . $value;
        }
    }
    return implode(PHP_EOL, $ret);
}

/**
 *
 * @param type $variable
 * @param type $title 
 */
function _dbg($variable, $title = "") {
    if (DEBUG) {
        if (is_string($variable) && $variable != "") {
            //$variable = htmlentities($variable);
        }
        echo '<div class="_dbg" style="width:700px;margin:0 auto;border:1px dashed grey;border-radius:2px;background-color:#eeeeee;margin-bottom:5px;padding:4px">';
        if ($title) {
            echo '<strong>', $title, '</strong>';
        }
        echo '<pre>', var_dump($variable), '</pre>';
        echo '</div>';
    }
}

?>

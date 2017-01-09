<?php

function keepXLines($str, $num=10) {
    $lines = explode("\n", $str);
    $firsts = array_slice($lines, 0, $num);
    return implode("\n", $firsts);
}

function D($v) {
	print '<pre>';
	var_dump($v);
	print '</pre>';
}

function DX($v) {
	print '<pre>';
	var_dump($v);
	print '</pre>';
	die;
}

function roundToHalf($num)
{
  if($num >= ($half = ($ceil = ceil($num))- 0.5) + 0.25) return $ceil;
  else if($num < $half - 0.25) return floor($num);
  else return $half;
}

function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
} 

function timeAgo ($ptime) {
	if (!is_int($ptime)) {
		$ptime = strtotime($ptime);
	}

	$etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 secs';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'min',
                                 1  =>  'sec'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'min' => 'mins',
                       'sec' => 'secs'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

function linkify ($text, $blank = true) {
	if ($blank) {
		$blank = ' target="_blank"';
	}

	return preg_replace(
	              "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
	              "<a href=\"\\0\"$blank>\\0</a>", 
	              $text);
}

function encode ($txt) {
	return Crypt::encrypt(gzcompress($txt));
}

function decode ($txt) {
    try {
	   return gzuncompress(Crypt::decrypt($txt));
    } catch (Exception $e) {
        throw new Exception('Decryption failed');
    }
}

function toClickableUrl ($url) {
	if (empty($url)) {
		return 'javascript:void(0)';
	}

	if (substr($url,0,4) == 'http') {
		return $url;
	}
	
	return 'http://' . $url;
}

function addComma ($key, $items, &$return, $space = ' ') {
	if (count($items) > $key + 1) {
		$return .= ',' . $space;
	}
}

function to_array($val) {
    return json_decode(json_encode($val), true);
}

function sortBy($field, &$array, $direction = 'asc') {
	$direction = strtolower($direction);

    usort($array, create_function('$a, $b', '
        $a = $a["' . $field . '"];
        $b = $b["' . $field . '"];

        if ($a == $b)
        {
            return 0;
        }

        return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
    '));

    return true;
}

function runline () {
	$return = '<br>';

	foreach (debug_backtrace() as $one) {
		$return .= $one['file'] . ': ' . (isset($one['class']) ? $one['class'] . '->' : '') . $one['function'] . '<br>';
	}

	return $return;
}

 function jsStr($s) {
	return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

 function toJsArray($array) {
	$temp = array_map('self::jsStr', $array);
	return '[' . implode(',', $temp) . ']';
}

 function getVarByPattern ($pattern, $target, $indicator = '%') {
	preg_match($pattern, $target, $matches);
	return str_replace($indicator,'', $matches[0]);
}

 function replacePatternToClassAsset ($pattern, $target, $class, $indicator = '%') {
	$return = $target;

	preg_match($pattern, $target, $matches);

	foreach ($matches as $one) {
		$varOrMethod = str_replace($indicator,'',$one);

		if (strstr($varOrMethod, '(') !== false) {
			$varOrMethod = str_replace(array('(',')'), '', $varOrMethod);
			
			$return = str_replace(
							$one,
							call_user_func(array($class, $varOrMethod)),
							$return
						);
		} else {
			$return = str_replace($one, $class->$varOrMethod, $return);
		}
	}

	return $return;
}

 function getValue ($array, $key, $default = false) {
	return isset($array[$key]) && $array[$key] != '' ? $array[$key] : $default;
}

 function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

 function setIfExists(&$target, $source, $value) {
	if (getValue($source, $value) !== false) {
		$target[$value] = $source[$value];
	}
}

 function setIfExistsMulti(&$target, $source, $values) {
	foreach($values as $value) {
		setIfExists($target, $source, $value);
	}
}

 function generateRandomString ($length = 6, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
	$count = mb_strlen($chars);

	for ($i = 0, $result = ''; $i < $length; $i++) {
		$index = rand(0, $count - 1);
		$result .= mb_substr($chars, $index, 1);
	}

	return $result;
}

 function sortByOneKey(array $array, $key, $asc = true) {
	$result = array();
	   
	$values = array();
	foreach ($array as $id => $value) {
		$values[$id] = isset($value[$key]) ? $value[$key] : '';
	}

	if ($asc) {
		asort($values);
	}
	else {
		arsort($values);
	}
	   
	foreach ($values as $key => $value) {
		$result[$key] = $array[$key];
	}
	   
	return $result;
}



 function truncate($str, $maxLen, $suffix = '...') {
 	if (strlen($str) <= $maxLen) {
 		return $str;
	}

	mb_internal_encoding('UTF-8');

	$substr = mb_substr($str, 0, $maxLen - strlen($suffix)).$suffix;
 	return $substr;
}

 function linkRewrite($str, $connector = '-', $utf8Decode = false) {
	$str = rtrim(strtolower($str));
	$convertTo = array(
		"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
		"v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
		"ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
		"з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
		"ь", "э", "ю", "я"
	);
	$convertFrom = array(
		"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
		"V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
		"Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
		"З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
		"Ь", "Э", "Ю", "Я"
	);

	$str = str_replace($convertFrom, $convertTo, $str);
	$from = explode(",","á,é,í,ö,ő,ó,ü,ű,ú");
	$to = explode(",","a,e,i,o,o,o,u,u,u");
	$str = str_replace($from, $to, $str);
	$purified = '';
	$length = strlen($str);

	if ($utf8Decode) {
		$str = utf8Decode($str);
	}

	for ($i = 0; $i < $length; $i++) {
		$char = substr($str, $i, 1);
		if (strlen(htmlentities($char)) > 1) {
			$entity = htmlentities($char, ENT_COMPAT, 'UTF-8');
			$purified .= @$entity{1};
		} elseif (preg_match('|[[:alpha:]]{1}|u', $char)) {
			$purified .= $char;
		} elseif (preg_match('<[[:digit:]]|-{1}>', $char)) {
			$purified .= $char;
		}  elseif ($char == ' ') {
			$purified .= $connector;
		}
	}

	return $purified;
}

 function createCaptcha ($wordLen, $font, $height = 80, $width = 120, $timeout = 3600, $imgDir = 'images/captcha') {
	$captcha = new Zend_Captcha_Image();

	$captcha->setTimeout($timeout)
	->setWordLen($wordLen)
	->setHeight($height)
	->setWidth($width)
	->setFont($font)
	->setDotNoiseLevel(0)
	->setLineNoiseLevel(0)
	->setImgDir($imgDir);

	$captcha->generate();

	return $captcha;
}

 function isValidInArrayMulti(array $whatArray,array $where) {
	foreach ($whatArray as $what) {
		selfisValidInArray($what,$where);
	}
	return true;
}

 function isValidInArray($what, array $where) {
	if (array_key_exists($what,$where)) {
		if ($where[$what] == NULL) {
			throw new Exception('Following parameter is NULL: '.$what);
		}
	} else {
		throw new Exception('Missing or empty paramater: '.$what);
	}
	return true;
}

 function getNowDatetime() {
	$date = new DateTime();
	$date->setTimestamp(time());
	$datetime = $date->format('Y-m-d H:i:s');
	
	return $datetime;
}

 function getYoutubeVideoId($url) {
 	if (strstr($url, '.') === false) {
 		return $url;
 	}

	$pattern = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=embed/)[^&\n]+|(?<=v=)[^&\‌​n]+|(?<=youtu.be/)[^&\n]+#";
	if (preg_match($pattern, $url, $match)) {
		return $match[0];
	} else {
		return false;
	}
}

function isYoutubeId($id) {
    return preg_match('/^[a-zA-Z0-9_-]{11}$/', $id) > 0;
}

 function getVimeoVideoId($url) {
	$pattern = '/^(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*$/';
	if (preg_match($pattern, $url, $match)) {
		return $match[5];
	} else {
		return false;
	}
}

 function getRomanicNumber($integer, $upcase = true) { 
	$table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	$return = ''; 
	while ($integer > 0) { 
		foreach ($table as $rom => $arb) { 
			if ($integer >= $arb) { 
				$integer -= $arb; 
				$return .= $rom; 
				break; 
			}
		}
	}

	return $return; 
}

 function isValidImage($image, $allowedTypes = array()) {
	if (empty($allowedTypes)) {
		$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
	}
	$detectedType = exif_imagetype($image);
	if (in_array($detectedType, $allowedTypes)) {
		return true;
	} else {
		return false;
	}
}
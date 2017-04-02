<?php
/**
 * Helper Functions
 *
 * @package bgCMS
 * @author Simeon Lyubenov (ShakE) <office@webdevlabs.com>
 * @link https://www.webdevlabs.com
 * @copyright Copyright (c) 2016 Simeon Lyubenov. All rights reserved.
 * @license NON-EXCLUSIVE LICENSE / Non-redistributable code
 * @note Web Development Labs reserves all intellectual property rights, including copyrights and trademark rights.
 */

/**
 * Lang Helper function
 *
 * @param [type] $value
 * @return void
 */
function lang ($value) {
	$grp=before('.',$value);
	$val=after('.',$value);
	return \System\lang::$lang_vars[$grp][$val];
}

/**
 * Convert size format to bytes
 *
 * @param [type] $size
 * @param [type] $from
 * @return void
 */
function convert_to_bytes( $size, $from ) {
  $float = floatval( $size );
  switch( $from )
  {
    case 'MB' :            // Megabyte
      $float *= 1048600;
      break;
    case 'GB' :            // Gigabyte
      $float *= 1073700000;
      break;
    case 'KB' :            // Kilobyte
      $float *= 1024;
      break;
  }
  unset( $size, $from );
  return( $float );
}

/**
 * Convert Byte size value to readable format
 *
 * @param [type] $bytes
 * @param boolean $type_only
 * @return void
 */
function ByteSize($bytes, $type_only=false)  {
	$size = $bytes / 1024;
	if($size < 1024) {
		if ($type_only == false) {
			$size = number_format($size, 2);
//			$size .= ' KB';
		}else{
			$size = 'KB';
		}
	}
	else {
		if($size / 1024 < 1024) {
			if ($type_only == false) {
				$size = number_format($size / 1024, 2);
//				$size .= ' MB';
			}else{
				$size = 'MB';
			}
		}
		elseif ($size / 1024 / 1024 < 1024) {
			if ($type_only == false) {
				$size = number_format($size / 1024 / 1024, 2);
//				$size .= ' GB';
			}else{
				$size = 'GB';
			}
		}
	}
	return $size;
}

// after ('@', 'biohazard@online.ge');
// returns 'online.ge'
// from the first occurrence of '@'
function after ($dthis, $inthat) {
       if (!is_bool(@strpos($inthat, $dthis))) {
       	return @substr($inthat, @strpos($inthat,$dthis)+strlen($dthis));
       }else{
			return $inthat;
		}
}

// after_last ('[', 'sin[90]*cos[180]');
// returns '180]'
// from the last occurrence of '['
function after_last ($dthis, $inthat) {
        if (!is_bool(strrevpos($inthat, $dthis))) {
         return substr($inthat, strrevpos($inthat, $dthis)+strlen($dthis));
    	}else{
			return $inthat;
		}
}

function before ($dthis, $inthat) {
       if (!is_bool(strrevpos($inthat, $dthis))) {
	       return substr($inthat, 0, strpos($inthat, $dthis));
	    }else{
			return $inthat;
		}
}

// returns 'sin[90]*cos['
// from the last occurrence of '['
function before_last ($dthis, $inthat) {
       if (!is_bool(strrevpos($inthat, $dthis))) {
       	return substr($inthat, 0, strrevpos($inthat, $dthis));
       }else{
			return $inthat;
		}
}

// between ('@', '.', 'biohazard@online.ge');
// returns 'online'
// from the first occurrence of '@'
function between ($dthis, $that, $inthat) {
   return before($that, after($dthis, $inthat));
}

// between_last ('[', ']', 'sin[90]*cos[180]');
// returns '180'
// from the last occurrence of '['
function between_last ($dthis, $that, $inthat) {
     return after_last($dthis, before_last($that, $inthat));
}
function strrevpos($instr, $needle) {
       $rev_pos = strpos (strrev($instr), strrev($needle));
       if ($rev_pos===false) { return false; }
       else { return strlen($instr) - $rev_pos - strlen($needle); }
}

function multiarray_search($array, $key, $value) {
    while(isset($array[key($array)])){
        if($array[key($array)][$key] == $value){
            return key($array);
        }
        next($array);
    }
    return -1;
}

/**
 * Sort Array by field
 *
 * @param [type] $original
 * @param [type] $field
 * @param boolean $descending
 * @return void
 */
function sortArrayByField ($original, $field, $descending = false) {
            $sortArr = array();
            foreach ( $original as $key => $value )  {
                $sortArr[ $key ] = $value[ $field ];
            }

            if ( $descending ) {
                arsort( $sortArr );
            }
            else {
                asort( $sortArr );
            }

            $resultArr = array();
            foreach ( $sortArr as $key => $value ) {
                $resultArr[ $key ] = $original[ $key ];
            }
            return $resultArr;
}

/**
 * Simple value to percent convertion
 *
 * @param [type] $num_amount
 * @param [type] $num_total
 * @return void
 */
function percent($num_amount, $num_total) {
	$count1 = $num_amount / 100;
	$count2 = $count1 * $num_total;
	$count = number_format($count2, 0);
	return $count;
}

/**
 * Password generation function
 *
 * @param int $length
 * @param int $strength
 * @return void
 */
function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

/**
 * Read the output content of a function
 *
 * @param [type] $file
 * @return void
 */
function get_processed_content ($file) {
	ob_start();
	include $file;
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * Custom array_map_recursive function
 *
 * @param callable $func
 * @param array $arr
 * @return void
 */
function array_map_recursive(callable $func, array $arr) {
    array_walk_recursive($arr, function(&$v) use ($func) {
        $v = $func($v);
    });
    return $arr;
}

/**
 * Convert array text to UTF-8
 *
 * @param [type] $array
 * @return void
 */
function utf8_converter($array) {
    array_walk_recursive($array, function(&$item, $key) {
        if(!mb_detect_encoding($item, 'utf-8', true)){
        	$item = utf8_encode($item);
        }
    });
    return $array;
}

/**
 * Execute command in background
 *
 * @param [type] $cmd
 * @return void
 */
function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
		pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        exec($cmd . " > /dev/null &");  
    }
} 

/**
 * Rate Limiter function
 * 
 * @param int $rate
 * @param int $per
 * @return void
 * Usage:
$ratelimit = ratelimiter();
while (true) {
  $ratelimit();
  echo "foo".PHP_EOL;
}
or
limit batched requests against the Facebook Graph API at 600 requests per 600 seconds based on the size of the batch:

$ratelimit = ratelimiter(600, 600);
while (..) {
  ..

  $ratelimit(count($requests));
  $response = (new FacebookRequest(
    $session, 'POST', '/', ['batch' => json_encode($requests)]
  ))->execute();

  foreach ($response->..) {
    ..
  }
} 
 */
function ratelimiter($rate = 5, $per = 8) {
  $last_check = microtime(True);
  $allowance = $rate;

  return function ($consumed = 1) use (
    &$last_check,
    &$allowance,
    $rate,
    $per
  ) {
    $current = microtime(True);
    $time_passed = $current - $last_check;
    $last_check = $current;

    $allowance += $time_passed * ($rate / $per);
    if ($allowance > $rate)
      $allowance = $rate;

    if ($allowance < $consumed) {
      $duration = ($consumed - $allowance) * ($per / $rate);
      $last_check += $duration;
      usleep($duration * 1000000);
      $allowance = 0;
    }
    else
      $allowance -= $consumed;

    return;
  };
}
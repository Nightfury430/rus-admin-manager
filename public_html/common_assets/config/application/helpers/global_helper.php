<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_common_assets'))
{
    function is_common_assets($string)
    {
        return strpos($string, 'common_assets') !== false;
    }
}

function check_access_to_common_db(){
    return (basename(dirname(FCPATH)) === 'dev');
}


function get_asset_path($string, $add_path){
    if (strpos($string, 'common_assets') !== false) {
        return $string;
    } else {
        return $add_path . $string;
    }
}


function buildTree($flat, $pidKey, $idKey = null)
{
    if(count($flat) < 1){
        return array();
    }

    $grouped = array();
    foreach ($flat as $sub){
        $grouped[$sub[$pidKey]][] = $sub;
    }

    $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
        foreach ($siblings as $k => $sibling) {
            $id = $sibling[$idKey];
            if(isset($grouped[$id])) {
                $sibling['children'] = $fnBuilder($grouped[$id]);
            }
            $siblings[$k] = $sibling;
        }

        return $siblings;
    };

    $tree = $fnBuilder($grouped[0]);

    return $tree;
}

function removeDirectory($path)
{
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
    return;
}


function recursiveCopy($source, $dest, $overwrite = 0)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        if ($overwrite == 1){
            return copy($source, $dest);
        } else {
            if (file_exists($dest)){
                return false;
            } else {
                return copy($source, $dest);
            }
        }

    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        recursiveCopy("$source/$entry", "$dest/$entry", $overwrite);
    }

    // Clean up
    $dir->close();
    return true;
}



function is_zip($file){

	$file_info = pathinfo($file['name']);
	$name = $file_info['filename'];
	$ext = $file_info['extension'];

	if(strtolower($ext) !== 'zip'){
		return false;
	}

	return true;
}


function is_image($file){
    $whitelist_ext = array('jpeg','jpg','png');
    $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png');
    $file_info = pathinfo($file['name']);
    $name = $file_info['filename'];
    $ext = $file_info['extension'];



    if (!in_array($ext, $whitelist_ext)) {
        return false;
    }

    if (!in_array($file["type"], $whitelist_type)) {
        return false;
    }

    $info = getimagesize($file['tmp_name']);


    if (!$info) {
        return false;
    }

    return $info;
}

function find_all_files($dir)
{
    $root = scandir($dir);
    foreach($root as $value)
    {
        if($value === '.' || $value === '..') {continue;}
        if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;}
        foreach(find_all_files("$dir/$value") as $value)
        {
            $result[]=$value;
        }
    }
    return $result;
}

function is_image_uploaded($file){
	$whitelist_ext = array('jpeg','jpg','png','gif');
	$whitelist_type = array('image/jpeg', 'image/jpg', 'image/png','image/gif');
	$file_info = pathinfo($file);




	$ext = strtolower($file_info['extension']);

	if (!in_array($ext, $whitelist_ext)) {
		return false;
	}

	$info = getimagesize($file);

	if (!in_array($info["mime"], $whitelist_type)) {
		return false;
	}

	if (!$info) {
		return false;
	}

	return $info;
}



function is_dbs($file){

    $file_info = pathinfo($file['name']);

    if($file_info['extension'] !== 'dbs') return false;

    $json = json_decode( file_get_contents($file['tmp_name']) );


    if(!isset($json->project_settings)) return false;
//    if(!isset($json->room)) return false;
//    if(!isset($json->objects)) return false;

    return true;

}

function no_xss($value) {
    return htmlspecialchars(strip_tags($value));
}

function xssafe($data,$encoding='UTF-8')
{
	return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);
}
function xecho($data)
{
	echo xssafe($data);
}

function send_mail($email, $subject, $msg, $from, $file)
{


	$boundary = "--" . md5(uniqid(time()));
	$headers = "MIME-Version: 1.0\n";
	$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
	$headers .= "From: $from\n";
	$multipart = "--$boundary\n";
	$multipart .= "Content-Type: text/html; charset=utf-8\n";
	$multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
	$multipart .= "$msg\n\n";
	$message_part = "";
	foreach ($file as $key => $value) {
		if(is_string($value)){
			$fp = fopen($value, "r");
			$file = fread($fp, filesize($value));
			$message_part .= "--$boundary\n";

			if (strpos($value, '.png') !== false) {
				$message_part .= "Content-Type: image/jpeg\n";
			}
			if (strpos($value, '.jpg') !== false) {
				$message_part .= "Content-Type: image/jpeg\n";
			}
			if (strpos($value, '.dbs') !== false) {
				$message_part .= "Content-Type: image/jpeg\n";
			}
			if (strpos($value, '.pdf') !== false) {
				$message_part .= "Content-Type: application/pdf\n";
			}
			if (strpos($value, '.csv') !== false) {
				$message_part .= "Content-Type: text/csv\n";
			}
            if (strpos($value, '.xlsx') !== false) {
                $message_part .= "Content-Type: application/xlsx\n";
            }

			$message_part .= "Content-Transfer-Encoding: base64\n";
			$message_part .= "Content-Disposition: attachment; filename=\"$value\"\n\n";
			$message_part .= chunk_split(base64_encode($file)) . "\n";
		} else {
			$fp = fopen($value['tmp_name'], "r");
			$file = fread($fp, filesize($value['tmp_name']));
			$message_part .= "--$boundary\n";
			$message_part .= "Content-Type: ". $value['type'] ."\n";
			$message_part .= "Content-Transfer-Encoding: base64\n";
			$message_part .= "Content-Disposition: attachment; filename=" . $value['name'] . "\n\n";
			$message_part .= chunk_split(base64_encode($file)) . "\n";
		}


	}
	$multipart .= $message_part . "--$boundary--\n";

	mail($email, mb_encode_mimeheader($subject,"UTF-8"), $multipart, $headers);

}

function get_filename_from_url($url){
	return basename(parse_url($url, PHP_URL_PATH));
}


function base64ToImage($base64_string, $output_file)
{
	$file = fopen($output_file, "wb");
	$data = explode(',', $base64_string);
	fwrite($file, base64_decode($data[1]));
	fclose($file);

	return $output_file;
}

function savedataToFile($data, $output_file){
	$file = fopen($output_file, "wb");
	fwrite($file, $data);
	fclose($file);
	return $output_file;
}


function base64ToPdf($base64_string, $output_file)
{
	$file = fopen($output_file, "wb");

	$data = explode(',', $base64_string);

	fwrite($file, base64_decode($data[1]));
	fclose($file);

	return $output_file;
}


function config_write( $config_data, $config_file ) {
	$new_content = '';
	foreach ( $config_data as $section => $section_content ) {
		$section_content = array_map( function ( $value, $key ) {
			return "$key=$value";
		}, array_values( $section_content ), array_keys( $section_content ) );
		$section_content = implode( "\n", $section_content );
		$new_content     .= "[$section]\n$section_content\n";
	}
	file_put_contents( $config_file, $new_content );
}

function get_default_lang(){

	include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/lng.php';

	if(!isset($lang_arr)) $lang_arr = array();

	return $lang_arr;

}

function get_default_lang_front(){

	include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/lng_front.php';

	if(!isset($lang_arr_front)) $lang_arr_front = array();

	return $lang_arr_front;

}



function getTotalSize($dir, $unit = 'm')
{
	$dir = rtrim(str_replace('\\', '/', $dir), '/');

	if (is_dir($dir) === true) {
		$totalSize = 0;
		$os        = strtoupper(substr(PHP_OS, 0, 3));
		// If on a Unix Host (Linux, Mac OS)
		if ($os !== 'WIN') {
			$io = popen('/usr/bin/du -sb ' . $dir, 'r');
			if ($io !== false) {
				$totalSize = intval(fgets($io, 80));
				pclose($io);
				return $totalSize;
			}
		}
		// If on a Windows Host (WIN32, WINNT, Windows)
		if ($os === 'WIN' && extension_loaded('com_dotnet')) {
			$obj = new \COM('scripting.filesystemobject');
			if (is_object($obj)) {
				$ref       = $obj->getfolder($dir);
				$totalSize = $ref->size;
				$obj       = null;
				return $totalSize;
			}
		}
		// If System calls did't work, use slower PHP 5
		$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
		foreach ($files as $file) {

			try {
				$totalSize += $file->getSize();
			} catch (Exception $e) {

			}
		}

		switch ($unit) {
			case 'g': $totalSize = number_format($totalSize / 1073741824, 3); break;  // giga
			case 'm': $totalSize = number_format($totalSize / 1048576, 1);    break;  // mega
			case 'k': $totalSize = number_format($totalSize / 1024, 0);       break;  // kilo
			case 'b': $totalSize = number_format($totalSize, 0);              break;  // byte
		}

		return $totalSize;



	} else if (is_file($dir) === true) {
		return filesize($dir);
	}
}

function rus2translit($string) {
	$converter = array(
		'а' => 'a',   'б' => 'b',   'в' => 'v',
		'г' => 'g',   'д' => 'd',   'е' => 'e',
		'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
		'и' => 'i',   'й' => 'y',   'к' => 'k',
		'л' => 'l',   'м' => 'm',   'н' => 'n',
		'о' => 'o',   'п' => 'p',   'р' => 'r',
		'с' => 's',   'т' => 't',   'у' => 'u',
		'ф' => 'f',   'х' => 'h',   'ц' => 'c',
		'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
		'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
		'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

		'А' => 'A',   'Б' => 'B',   'В' => 'V',
		'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
		'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
		'И' => 'I',   'Й' => 'Y',   'К' => 'K',
		'Л' => 'L',   'М' => 'M',   'Н' => 'N',
		'О' => 'O',   'П' => 'P',   'Р' => 'R',
		'С' => 'S',   'Т' => 'T',   'У' => 'U',
		'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
		'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
		'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
		'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
	);
	return strtr($string, $converter);
}



function print_pre($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

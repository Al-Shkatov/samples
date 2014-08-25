<?php

class FileUploader {

    private static $icons = array(
        'avi' => 'admin/images/file_icons/avi.png',
        'doc' => 'admin/images/file_icons/doc.png',
        'docx' => 'admin/images/file_icons/doc.png',
        'xls' => 'admin/images/file_icons/xls.png',
        'xlsx' => 'admin/images/file_icons/xls.png',
        'mp3' => 'admin/images/file_icons/mp3.png',
        'iso' => 'admin/images/file_icons/iso.png',
        'mpeg' => 'admin/images/file_icons/mpeg.png',
        'mp4' => 'admin/images/file_icons/video.png',
        'pdf' => 'admin/images/file_icons/pdf.png',
        'txt' => 'admin/images/file_icons/txt.png',
        'swf' => 'admin/images/file_icons/swf.png',
        'zip' => 'admin/images/file_icons/ar.png',
        'rar' => 'admin/images/file_icons/ar.png',
        'gzip' => 'admin/images/file_icons/ar.png',
        'default' => 'admin/images/file_icons/default.png'
    );
    private static $user_icons = array(
        'avi' => 'upload/file_icons/avi.png',
        'doc' => 'upload/file_icons/doc.png',
        'docx' => 'upload/file_icons/doc.png',
        'xls' => 'upload/file_icons/xls.png',
        'xlsx' => 'upload/file_icons/xls.png',
        'mp3' => 'upload/file_icons/mp3.png',
        'iso' => 'upload/file_icons/iso.png',
        'mpeg' => 'upload/file_icons/mpeg.png',
        'mp4' => 'upload/file_icons/video.png',
        'pdf' => 'upload/file_icons/pdf.png',
        'txt' => 'upload/file_icons/txt.png',
        'swf' => 'upload/file_icons/swf.png',
        'zip' => 'upload/file_icons/ar.png',
        'rar' => 'upload/file_icons/ar.png',
        'gzip' => 'upload/file_icons/ar.png',
        'default' => 'upload/file_icons/default.png'
    );
    private static $fileTypes = array(
        'jpg' => 'image',
        'jpeg' => 'image',
        'gif' => 'image',
        'png' => 'image',
        'jpeg' => 'image',
        'doc' => 'document',
        'docx' => 'document',
        'xls' => 'document',
        'xlsx' => 'document',
        'pdf' => 'pdf'
    );
    private static $userFileTypes = array(
        'doc' => 'MS DOC',
        'docx' => 'MS DOCX',
        'xls' => 'MS XLS',
        'xlsx' => 'MS XLSX',
    );
    private static $userFullFileTypes = array(
        'doc' => 'ms_word_document',
        'docx' => 'ms_word_document',
        'xls' => 'ms_exel_document',
        'xlsx' => 'ms_exel_document'
    );

    public static function getUserFullFileType($file) {
        $path_parts = pathinfo($file);
        $ext = strtolower($path_parts["extension"]);
        return isset(self::$userFullFileTypes[$ext]) ? self::$userFullFileTypes[$ext] : $ext;
    }

    public static function getUserFileType($file) {
        $path_parts = pathinfo($file);
        $ext = strtolower($path_parts["extension"]);
        if (!empty(self::$userFileTypes[$ext])) {
            return self::$userFileTypes[$ext];
        } else {
            return strtoupper($ext);
        }
    }

    public static function getIcon($file, $user = false) {
        $path_parts = pathinfo($file);
        $ext = strtolower($path_parts["extension"]);
        $icons = $user ? self::$user_icons : self::$icons;
        if (!empty($icons[$ext])) {
            return $icons[$ext];
        } else {
            return $icons['default'];
        }
    }

    public static function getFileType($file) {
        $path_parts = pathinfo($file);
        $ext = strtolower($path_parts["extension"]);
        if (!empty(self::$fileTypes[$ext])) {
            return self::$fileTypes[$ext];
        } else {
            return 'unknown';
        }
    }

    public static function getFileExt($file) {
        $path_parts = pathinfo($file);
        return strtolower($path_parts["extension"]);
    }

    public static function getFileFullName($file) {
        $path_parts = pathinfo($file);
        return $path_parts["filename"] . '.' . $path_parts["extension"];
    }

    public static function getFileName($file) {
        $path_parts = pathinfo($file);
        return $path_parts["filename"];
    }

    public function download($filename) {
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        $file_extension = strtolower(FileUploader::getFileExt($filename));

        switch ($file_extension) {
            case "pdf": $ctype = "application/pdf";
                break;
            case "exe": $ctype = "application/octet-stream";
                break;
            case "zip": $ctype = "application/zip";
                break;
            case "doc": $ctype = "application/msword";
                break;
            case "docx": $ctype = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                break;
            case "xls": $ctype = "application/vnd.ms-excel";
                break;
            case "xlsx": $ctype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                break;
            case "ppt": $ctype = "application/vnd.ms-powerpoint";
                break;
            case "gif": $ctype = "image/gif";
                break;
            case "png": $ctype = "image/png";
                break;
            case "jpeg": $ctype = "image/jpg";
                break;
            case "jpg": $ctype = "image/jpg";
                break;
            default: $ctype = "application/force-download";
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header("Content-Type: $ctype");
        // change, added quotes to allow spaces in filenames, by Rajkumar Singh
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($filename));
        echo file_get_contents($filename);
        //readfile("$filename");
        exit();
    }

    /*
      public static function fileSize($file, $setup = null)
      {
      $FZ = ($file && @is_file($file)) ? filesize($file) : NULL;

      $FS = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");

      if(!$setup && $setup !== 0)
      {
      return number_format($FZ/pow(1024, $I=floor(log($FZ, 1024))), ($i >= 1) ? 2 : 0) . ' ' . $FS[$I];
      } elseif ($setup == 'INT') return number_format($FZ);
      else return number_format($FZ/pow(1024, $setup), ($setup >= 1) ? 2 : 0 ). ' ' . $FS[$setup];
      }
     */

    public static function fileSize($file, $decimals = 2, $lng = 'en') {
        $bytes = ($file && @is_file($file)) ? filesize($file) : NULL;
        $sz['en'] = 'B,K,M,G,T,P';
        $sz['ua'] = 'Б,К,М,Г,Т,П';
        $sz_lng = mb_split(',', $sz[$lng]);
        $b = array('ua' => 'Б', 'en' => 'B');
        $factor = floor((strlen($bytes) - 1) / 3);
        $res = sprintf("%.{$decimals}f", $bytes / pow(1024, $factor));
        $res = rtrim($res, '0.');
        $res = $res . @$sz_lng[$factor];
        $res = (@$sz_lng[$factor] == $b[$lng]) ? $res : $res . $b[$lng];
        return $res;
    }

    public static function upload($upload_folder, $unique_name = true) {
        $request = Factory::getRegistry()->request;
        $file_types = self::$fileTypes;
        // HTTP headers for no cache etc
        // header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        // header("Cache-Control: no-store, no-cache, must-revalidate");
        // header("Cache-Control: post-check=0, pre-check=0", false);
        // header("Pragma: no-cache");
// Settings
//$targetDir = ini_get("upload_tmp_dir") . '/' . "plupload";
        $targetDir = ROOT_DIR . '/' . $upload_folder;
//        if (!is_dir($targetDir . '/' . date('Y'))) {
//            mkdir($targetDir . '/' . date('Y'), 0777);
//        }
        
//        $targetDir = $targetDir . '/' . date('Y');
//        if (!is_dir($targetDir . '/' . date('m'))) {
//            mkdir($targetDir . '/' . date('m'), 0777);
//        }
//        
//        $targetDir = $targetDir . '/' . date('m');
        
        $upload_folder = $upload_folder;

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
// 5 minutes execution time
        @set_time_limit(5 * 60);

// Uncomment this one to fake upload time

        $chunk = $request->getParam('chunk') ? (int) $request->getParam('chunk') : 0;
        $chunks = $request->getParam('chunks') ? (int) $request->getParam('chunks') : 0;
        $fileName = $request->getParam('name') ? $request->getParam('name') : '';
        $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);


        if ($unique_name) {
            $file_ext = FileUploader::getFileExt($fileName);
            $fileName = FileUploader::getFileName($fileName);
//            $fileName.='_' . round(microtime(1));
            $fileName.='.' . $file_ext;
        }
        if ($chunks < 2 && file_exists($targetDir . '/' . $fileName)) {
            $ext = strrpos($fileName, '.');
            $fileName_a = substr($fileName, 0, $ext);
            $fileName_b = substr($fileName, $ext);
            $count = 1;
            while (file_exists($targetDir . '/' . $fileName_a . '_' . $count . $fileName_b)) {
                $count++;
            }

            $fileName = $fileName_a  . '_' . $count  . $fileName_b;
        }
        $baseUrl = '';
        $filePath = $targetDir . '/' . $fileName;


        if (!file_exists($targetDir))
            @mkdir($targetDir);
        $dir = opendir($targetDir);
        if ($cleanupTargetDir && is_dir($targetDir) && $dir != false) {
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . '/' . $file;
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
                    @unlink($tmpfilePath);
                }
            }

            closedir($dir);
        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }

        if (isset($_SERVER["HTTP_CONTENT_TYPE"])) {
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];
        }

        if (isset($_SERVER["CONTENT_TYPE"])) {
            $contentType = $_SERVER["CONTENT_TYPE"];
        }
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                }
            } else {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
        } else {
            $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
            if ($out) {
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }

                fclose($in);
                fclose($out);
            } else {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
        }
        if (!$chunks || $chunk == $chunks - 1) {
            rename("{$filePath}.part", $filePath);
        }
        //echo $fileName;

        $ext = explode('.', $fileName);
        $ext = $ext[sizeof($ext) - 1];
        $response = array(
            'name' => $fileName,
            'path' => $filePath,
            'url' => $baseUrl . $upload_folder . '/' . $fileName,
            'size' => (round((filesize($filePath) / 1024), 2) . 'kB'),
            'type' => $file_types[strtolower($ext)],
            'ext' => $ext,
            'modified' => date('d.m.Y', filemtime($filePath)),
            'modifiedSort' => date('d.m.Y H:i:s', filemtime($filePath)),
            'enabled' => true,
        );

        if ($response['type'] == 'image') {
            $response['thumb'] = $baseUrl . Image::resize($filePath, 70, 50);
        } else {
            $response['thumb'] = self::$icons[strtolower($ext)];
            $response['thumb'] = empty($response['thumb']) ? self::$icons['default'] : $response['thumb'];
        }

        die('{"jsonrpc" : "2.0", "result" : ' . json_encode($response) . ', "id" : "id"}');
    }

}

?>

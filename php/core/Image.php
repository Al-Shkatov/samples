<?php

class Image
{

    public function __construct()
    {
     
    }

    
    public static function getWidth($path){
        $size = getimagesize($path); 
        return $size[0];
    }

    public static function getHeight($path){
        $size = getimagesize($path); 
        return $size[1];
    }


    public static function resize($path, $width=0, $height=0, $type=1, $grayscale = false, $force_resize = false)
    {
        
        /*
            $type = 1 : домальовує
            $type = 2 : обрізає
        */
        if(FileUploader::getFileType($path)!='image'){
            return FileUploader::getIcon($path);
        }
        if(!file_exists($path)){
            return '';
        }
        list($real_width,$real_height) = getimagesize($path);
        $ratio = $real_width/$real_height;
        if($width==0){
            $width = $ratio*$height;
        }
        if($height==0){
            $height = $width/$ratio;
        }
        if($type==3){
            $width = $width>$real_width?$real_width:$width;
            $height = $height>$real_height?$real_height:$height;
            $scale = min($width / $real_width, $height / $real_height);
            $width = $real_width*$scale;
            $height = $real_height*$scale;
            
        }
        
        
        $dir = explode('/', $path);
        $patch_parts = pathinfo($path);
        $name = array($patch_parts['filename'],$patch_parts['extension']);
        $ext = $name[1];
        $grayscale_name = $grayscale?'1':'0';
        $name = $name[0] . '_' . $width . 'x' . $height . '_' .$type. '_' .$grayscale_name. '.'.strtolower($ext);
        array_pop($dir);
        $file_dir = strtolower(substr($name,0,1));
        if(preg_match('([a-z0-9-_]+)',$file_dir) == 0){
            $file_dir = 'other';
        }

       
  
        $cache_dir = 'cache/thumbnails/';
        $dir = ROOT_DIR . '/'.$cache_dir;
        if(!is_dir($dir. '/'.$file_dir)){
            mkdir($dir. '/'.$file_dir,0777);
        }
        
        $cache_dir = $cache_dir.$file_dir.'/';
        $dir = $dir.$file_dir.'/';        
    
        if(is_file($dir . $name)&&$force_resize){
            unlink($dir . $name);
        }
   
        if (!is_file($dir . $name)||$force_resize)
        {
            switch (strtolower($ext))
            {
                case'jpg':
                case'jpeg':
                    $crFunc = 'imagecreatefromjpeg';
                    $sFunc = 'imagejpeg';
                    break;
                case'gif':
                    $crFunc = 'imagecreatefromgif';
                    $sFunc = 'imagegif';
                    break;
                case'png':
                    $crFunc = 'imagecreatefrompng';
                    $sFunc = 'imagepng';
                    break;
                default :
                    break;
            }
            
            $source_image = $crFunc($path);
            $swidth = imagesx($source_image);
            $sheight = imagesy($source_image);
            $xpos = 0;
            $ypos = 0;
            
            if($type==2)
            {
                $scale = max($width / $swidth, $height / $sheight);
            }else{
                $scale = min($width / $swidth, $height / $sheight);
            }

            

            if ($scale == 1 && !$grayscale)
            {
                copy($path,$dir . $name);
                return $cache_dir.$name;
            }
                   
            $new_width = (int) ($swidth * $scale);
            $new_height = (int) ($sheight * $scale);
            $xpos = (int) (($width - $new_width) / 2);
            $ypos = (int) (($height - $new_height) / 2);

            $dst_image = imagecreatetruecolor($width, $height);
            
            
            if($ext=='png'){
                $background = imagecolorallocatealpha($dst_image, 255, 255, 255, 127);
                imagealphablending($dst_image, false);
                imagesavealpha($dst_image, true);
            }  else
            {
                $background = imagecolorallocate($dst_image, 255, 255, 255);
            }
            
            

            imagefilledrectangle($dst_image, 0, 0, $width, $height, $background);
            imagefill($dst_image,0,0,$background);
            imagecopyresampled($dst_image, $source_image, $xpos, $ypos, 0, 0, $new_width, $new_height, $swidth, $sheight);
            if($grayscale){
                imagefilter($dst_image, IMG_FILTER_GRAYSCALE);
            }
            $sFunc($dst_image, $dir . $name);
            imagedestroy($source_image);
        }
       return $cache_dir.$name;
    }

}

?>

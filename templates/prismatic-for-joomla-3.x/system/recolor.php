<?php
/**
 * @package     Prismatic
 * @author      Robin Jungermann
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

if(function_exists('imagecreatefrompng') && function_exists('imagefilter')){

    jimport('joomla.filesystem.file');

    function ct_colorize_pics($sourcePath, $destPath, $r, $g, $b){

        if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {

                while (false !== ($file = @readdir($handle))) {

                        $ext = strtolower(JFile::getExt($sourcePath.$file));
                    
                        if($file != "." && $file != ".." && ( $ext == 'png' || $ext == 'jpg'  || $ext == 'jpeg') ){

                            if($ext == 'png'){
                                $im = imagecreatefrompng( $sourcePath.$file );
                            }else{
                                $im = imagecreatefromjpeg( $sourcePath.$file );
                            }
                            
                            // prevent CONEXT updaters from wondering why the background images won't load anymore
                            if( JFactory::getApplication()->getTemplate() == 'CONEXT' && stripos($sourcePath, 'bg_images_source') !== false ){
                                
                                // keep alphablending default
                                
                            } else {
                                
                                // turn off alphablending for images that are not explicetly marked to use in the filename
                                if( stripos($file, '_alphablending') === false ){
                                    imagealphablending($im, false);
                                }
                            }
                            
                            imagefilter($im, IMG_FILTER_COLORIZE, intval($r), intval($g), intval($b));
                            imagesavealpha ( $im , true );
                            
                            if( stripos($file, '_multiply') !== false  ){
                            
                                if($ext == 'png'){
                                    $im2 = imagecreatefrompng( $sourcePath.$file );
                                } else {
                                    $im2 = imagecreatefromjpeg( $sourcePath.$file );
                                }
                                
                                imagelayereffect($im2, IMG_EFFECT_OVERLAY);

                                $w = imagesx($im);
                                $h = imagesy($im);
                                imagecopy($im2, $im, 0,0, 0,0, $w,$h); 
                                imagesavealpha ( $im2, true );

                                ob_start();
                                imagepng( $im2 );
                                $c = ob_get_contents();
                                ob_end_clean();
                                JFile::write( $destPath.$file, $c );

                                imagedestroy( $im );
                                imagedestroy( $im2 );
                            
                            } else {
                                
                                ob_start();
                                imagepng( $im );
                                $c = ob_get_contents();
                                ob_end_clean();
                                JFile::write( $destPath.$file, $c );
                                imagedestroy( $im );
                            }
                        }
                }
        }
    }

    $text_lastcolor = '';
    $lastcolor = '';
	$button_text_lastcolor = '';
    
    ####################
    
    if( JFile::exists(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'system'.DS.'text_lastcolor.txt') ){
        $text_lastcolor = JFile::read(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'system'.DS.'text_lastcolor.txt');
    }

    $r = hexdec(substr( str_replace('#','',$this->params->get('text_color')), 0, 2 ));
    $g = hexdec(substr( str_replace('#','',$this->params->get('text_color')), 2, 2 ));
    $b = hexdec(substr( str_replace('#','',$this->params->get('text_color')), 4, 2 ));

    if ($this->params->get('text_color') != '' && $r.$g.$b != $text_lastcolor) {

        $sourcePath  = JPATH_SITE . DS . 'templates' . DS . $this->template . DS . 'text_images_source' . DS;
        $destPath    = JPATH_SITE . DS . 'templates' . DS . $this->template . DS . 'images' . DS;

        ct_colorize_pics($sourcePath, $destPath, $r, $g, $b);

        JFile::write(JPATH_SITE.DS.'templates'.DS.JFactory::getApplication()->getTemplate().DS.'system'.DS.'text_lastcolor.txt', $dechex = $r.$g.$b);

    }

    ####################
    
    if( JFile::exists(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'system'.DS.'lastcolor.txt') ){
        $lastcolor = JFile::read(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'system'.DS.'lastcolor.txt');
    }

    $r = hexdec(substr( str_replace('#','',$this->params->get('accent_color')), 0, 2 ));
    $g = hexdec(substr( str_replace('#','',$this->params->get('accent_color')), 2, 2 ));
    $b = hexdec(substr( str_replace('#','',$this->params->get('accent_color')), 4, 2 ));

    if ($this->params->get('accent_color') != '' && $r.$g.$b != $lastcolor) {

        $sourcePath = JPATH_SITE . DS . 'templates' . DS . $this->template . DS . 'images_source' . DS;
        $destPath    = JPATH_SITE . DS . 'templates' . DS . $this->template . DS . 'images' . DS;

        ct_colorize_pics($sourcePath, $destPath, $r, $g, $b);

        JFile::write(JPATH_SITE.DS.'templates'.DS.JFactory::getApplication()->getTemplate().DS.'system'.DS.'lastcolor.txt', $dechex = $r.$g.$b);

    }

	####################
    
    if( JFile::exists(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'system'.DS.'button_text_lastcolor.txt') ){
        $button_text_lastcolor = JFile::read(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'system'.DS.'button_text_lastcolor.txt');
    }

    $r = hexdec(substr( str_replace('#','',$this->params->get('button_text_color')), 0, 2 ));
    $g = hexdec(substr( str_replace('#','',$this->params->get('button_text_color')), 2, 2 ));
    $b = hexdec(substr( str_replace('#','',$this->params->get('button_text_color')), 4, 2 ));

    if ($this->params->get('button_text_color') != '' && $r.$g.$b != $button_text_lastcolor) {

        $sourcePath = JPATH_SITE . DS . 'templates' . DS . $this->template . DS . 'button_images_source' . DS;
        $destPath    = JPATH_SITE . DS . 'templates' . DS . $this->template . DS . 'images' . DS;

        ct_colorize_pics($sourcePath, $destPath, $r, $g, $b);

        JFile::write(JPATH_SITE.DS.'templates'.DS.JFactory::getApplication()->getTemplate().DS.'system'.DS.'button_text_lastcolor.txt', $dechex = $r.$g.$b);

    }
}

<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010-2013 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
 
    define('QR_IMAGE', true);


    /** @defgroup OutputGroup Standard API Output
    Provide simple Raster & Vector output */
     
    /** @addtogroup OutputGroup */
    /** @{ */
    
    /** Image rendering helper.
    Uses GD2 image to render QR Code into image file */
    class QRimage {
    
        //----------------------------------------------------------------------
        /**
        Creates PNG image.
        @param Array $frame frame containing code
        @param String $filename (optional) output file name, if __false__ outputs to browser with required headers
        @param Integer $pixelPerPoint (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $outerFrame (optional) code margin (silent zone) in 'virtual'  pixels
        @param Boolean $saveandprint (optional) if __true__ code is outputed to browser and saved to file, otherwise only saved to file. It is effective only if $outfile is specified.
        */
        
        public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            
            if ($filename === false) {
                Header("Content-type: image/png");
                ImagePng($image);
            } else {
                if($saveandprint===TRUE){
                    ImagePng($image, $filename);
                    header("Content-type: image/png");
                    ImagePng($image);
                }else{
                    ImagePng($image, $filename);
                }
            }
            
            ImageDestroy($image);
        }
    
        //----------------------------------------------------------------------
        /**
        Creates JPEG image.
        @param Array $frame frame containing code
        @param String $filename (optional) output file name, if __false__ outputs to browser with required headers
        @param Integer $pixelPerPoint (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $outerFrame (optional) code margin (silent zone) in 'virtual'  pixels
        @param Integer $q (optional) JPEG compression level (__0__ .. __100__)
        */
        
        public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            
            if ($filename === false) {
                Header("Content-type: image/jpeg");
                ImageJpeg($image, null, $q);
            } else {
                ImageJpeg($image, $filename, $q);            
            }
            
            ImageDestroy($image);
        }
    
        //----------------------------------------------------------------------
        /**
        Creates generic GD2 image object
        @param Array $frame frame containing code
        @param Integer $pixelPerPoint (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $outerFrame (optional) code margin (silent zone) in 'virtual'  pixels
        @return __Resource__ GD2 image resource (remember to ImageDestroy it!)
        */
        public static function image($frame, $pixelPerPoint = 4, $outerFrame = 4) 
        {
            $h = count($frame);
            $w = strlen($frame[0]);
            
            $imgW = $w + 2*$outerFrame;
            $imgH = $h + 2*$outerFrame;
            
            $base_image =ImageCreate($imgW, $imgH);
            
            $col[0] = ImageColorAllocate($base_image,255,255,255);
            $col[1] = ImageColorAllocate($base_image,0,0,0);

            imagefill($base_image, 0, 0, $col[0]);

            for($y=0; $y<$h; $y++) {
                for($x=0; $x<$w; $x++) {
                    if ($frame[$y][$x] == '1') {
                        ImageSetPixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]); 
                    }
                }
            }
            
            $target_image =ImageCreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
            ImageCopyResized($target_image, $base_image, 0, 0, 0, 0, $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH);
            ImageDestroy($base_image);
            
            return $target_image;
        }
    }
    
    /** @} */
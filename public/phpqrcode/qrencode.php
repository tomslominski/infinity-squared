<?php
/*
 * PHP QR Code encoder
 *
 * Main encoder classes.
 *
 * Based on libqrencode C library distributed under LGPL 2.1
 * Copyright (C) 2006, 2007, 2008, 2009 Kentaro Fukuchi <fukuchi@megaui.net>
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
 
    /** @defgroup CoreGroup Standard API Core 
    Core encoder classes */
     
    /** @addtogroup CoreGroup */
    /** @{ */
     
    //##########################################################################
    /** 
    Data block with raw data and it's Error Correction Code data.
    */
    class QRrsblock {
        public $dataLength;
        public $data = array();
        public $eccLength;
        public $ecc = array();
        
        /** Data block Constructor
        @param Integer $dl length of data stream
        @param Array $data data stream
        @param Integer $el ECC length
        @param Array $el ECC stream (modified, by reference)
        @param QRrsItem $rs RS encoding item
        */
        public function __construct($dl, $data, $el, &$ecc, QRrsItem $rs)
        {
            $rs->encode_rs_char($data, $ecc);
        
            $this->dataLength = $dl;
            $this->data = $data;
            $this->eccLength = $el;
            $this->ecc = $ecc;
        }
    };
    
    //##########################################################################
    /** Raw Code holder.
    Contains encoded code data before there are spatialy distributed into frame and masked.
    Here goes dividing data into blocks and calculating ECC stream. */
    class QRrawcode {
    
        public $version;                ///< __Integer__ code Version
        public $datacode = array();     ///< __Array__ data stream
        public $ecccode = array();      ///< __Array__ ECC Stream
        public $blocks;                 ///< __Integer__ RS Blocks count
        public $rsblocks = array();     ///< __Array__ of RSblock, ECC code blocks
        public $count;                  ///< __Integer__ position of currently processed ECC code
        public $dataLength;             ///< __Integer__ data stream length
        public $eccLength;              ///< __Integer__ ECC stream length
        public $b1;                     ///< __Integer__ width of code in pixels, used as a modulo base for column overflow
        
        //----------------------------------------------------------------------
        /** Raw Code holder Constructor 
        @param QRinput $input input stream
        */
        public function __construct(QRinput $input)
        {
            $spec = array(0,0,0,0,0);
            
            $this->datacode = $input->getByteStream();
            if(is_null($this->datacode)) {
                throw new Exception('null imput string');
            }

            QRspec::getEccSpec($input->getVersion(), $input->getErrorCorrectionLevel(), $spec);

            $this->version = $input->getVersion();
            $this->b1 = QRspec::rsBlockNum1($spec);
            $this->dataLength = QRspec::rsDataLength($spec);
            $this->eccLength = QRspec::rsEccLength($spec);
            $this->ecccode = array_fill(0, $this->eccLength, 0);
            $this->blocks = QRspec::rsBlockNum($spec);
            
            $ret = $this->init($spec);
            if($ret < 0) {
                throw new Exception('block alloc error');
                return null;
            }

            $this->count = 0;
        }
        
        //----------------------------------------------------------------------
        /** Initializes Raw Code according to current code speciffication
        @param Array $spec code speciffigation, as provided by QRspec
        */
        public function init(array $spec)
        {
            $dl = QRspec::rsDataCodes1($spec);
            $el = QRspec::rsEccCodes1($spec);
            $rs = QRrs::init_rs(8, 0x11d, 0, 1, $el, 255 - $dl - $el);
            

            $blockNo = 0;
            $dataPos = 0;
            $eccPos = 0;
            for($i=0; $i<QRspec::rsBlockNum1($spec); $i++) {
                $ecc = array_slice($this->ecccode,$eccPos);
                $this->rsblocks[$blockNo] = new QRrsblock($dl, array_slice($this->datacode, $dataPos), $el,  $ecc, $rs);
                $this->ecccode = array_merge(array_slice($this->ecccode,0, $eccPos), $ecc);
                
                $dataPos += $dl;
                $eccPos += $el;
                $blockNo++;
            }

            if(QRspec::rsBlockNum2($spec) == 0)
                return 0;

            $dl = QRspec::rsDataCodes2($spec);
            $el = QRspec::rsEccCodes2($spec);
            $rs = QRrs::init_rs(8, 0x11d, 0, 1, $el, 255 - $dl - $el);
            
            if($rs == NULL) return -1;
            
            for($i=0; $i<QRspec::rsBlockNum2($spec); $i++) {
                $ecc = array_slice($this->ecccode,$eccPos);
                $this->rsblocks[$blockNo] = new QRrsblock($dl, array_slice($this->datacode, $dataPos), $el, $ecc, $rs);
                $this->ecccode = array_merge(array_slice($this->ecccode,0, $eccPos), $ecc);
                
                $dataPos += $dl;
                $eccPos += $el;
                $blockNo++;
            }

            return 0;
        }
        
        //----------------------------------------------------------------------
        /** Gets ECC code 
        @return Integer ECC byte for current object position
        */
        public function getCode()
        {
            $ret;

            if($this->count < $this->dataLength) {
                $row = $this->count % $this->blocks;
                $col = $this->count / $this->blocks;
                if($col >= $this->rsblocks[0]->dataLength) {
                    $row += $this->b1;
                }
                $ret = $this->rsblocks[$row]->data[$col];
            } else if($this->count < $this->dataLength + $this->eccLength) {
                $row = ($this->count - $this->dataLength) % $this->blocks;
                $col = ($this->count - $this->dataLength) / $this->blocks;
                $ret = $this->rsblocks[$row]->ecc[$col];
            } else {
                return 0;
            }
            $this->count++;
            
            return $ret;
        }
    }

    //##########################################################################
    /** 
    __Main class to create QR-code__.
    QR Code symbol is a 2D barcode that can be scanned by handy terminals such as a mobile phone with CCD.
    The capacity of QR Code is up to 7000 digits or 4000 characters, and has high robustness.
    This class supports QR Code model 2, described in JIS (Japanese Industrial Standards) X0510:2004 or ISO/IEC 18004.
    
    Currently the following features are not supported: ECI and FNC1 mode, Micro QR Code, QR Code model 1, Structured mode.
    
    @abstract Class for generating QR-code images, SVG and HTML5 Canvas 
    @author Dominik Dzienia
    @copyright 2010-2013 Dominik Dzienia and others
    @link http://phpqrcode.sourceforge.net
    @license http://www.gnu.org/copyleft/lesser.html LGPL
    */

    class QRcode {
    
        public $version;    ///< __Integer__ QR code version. Size of QRcode is defined as version. Version is from 1 to 40. Version 1 is 21*21 matrix. And 4 modules increases whenever 1 version increases. So version 40 is 177*177 matrix.
        public $width;      ///< __Integer__ Width of code table. Because code is square shaped - same as height.
        public $data;       ///< __Array__ Ready, masked code data.
        
        /** Canvas JS include flag.
        If canvas js support library was included, we remember it static in QRcode. 
        (because file should be included only once)
         */
        public static $jscanvasincluded = false;
        
        //----------------------------------------------------------------------
        /**
        Encode mask
        Main function responsible for creating code. 
        We get empty frame, then fill it with data from input, then select best mask and apply it.
        If $mask argument is greater than -1 we assume that user want's that specific mask number (ranging form 0-7) to be used.
        Otherwise (when $mask is -1) mask is detected using algorithm depending of global configuration,
        
        @param QRinput $input data object
        @param Integer $mask sugested masking mode
        @return QRcode $this (current instance)
        */
        public function encodeMask(QRinput $input, $mask)
        {
            if($input->getVersion() < 0 || $input->getVersion() > QRSPEC_VERSION_MAX) {
                throw new Exception('wrong version');
            }
            if($input->getErrorCorrectionLevel() > QR_ECLEVEL_H) {
                throw new Exception('wrong level');
            }

            $raw = new QRrawcode($input);
            
            QRtools::markTime('after_raw');
            
            $version = $raw->version;
            $width = QRspec::getWidth($version);
            $frame = QRspec::newFrame($version);
            
            $filler = new QRframeFiller($width, $frame);
            if(is_null($filler)) {
                return NULL;
            }

            // inteleaved data and ecc codes
            for($i=0; $i<$raw->dataLength + $raw->eccLength; $i++) {
                $code = $raw->getCode();
                $bit = 0x80;
                for($j=0; $j<8; $j++) {
                    $addr = $filler->next();
                    $filler->setFrameAt($addr, 0x02 | (($bit & $code) != 0));
                    $bit = $bit >> 1;
                }
            }
            
            QRtools::markTime('after_filler');
            
            unset($raw);
            
            // remainder bits
            $j = QRspec::getRemainder($version);
            for($i=0; $i<$j; $i++) {
                $addr = $filler->next();
                $filler->setFrameAt($addr, 0x02);
            }
            
            $frame = $filler->frame;
            unset($filler);
            
            
            // masking
            $maskObj = new QRmask();
            if($mask < 0) {
            
                if (QR_FIND_BEST_MASK) {
                    $masked = $maskObj->mask($width, $frame, $input->getErrorCorrectionLevel());
                } else {
                    $masked = $maskObj->makeMask($width, $frame, (intval(QR_DEFAULT_MASK) % 8), $input->getErrorCorrectionLevel());
                }
            } else {
                $masked = $maskObj->makeMask($width, $frame, $mask, $input->getErrorCorrectionLevel());
            }
            
            if($masked == NULL) {
                return NULL;
            }
            
            QRtools::markTime('after_mask');
            
            $this->version  = $version;
            $this->width    = $width;
            $this->data     = $masked;
            
            return $this;
        }
    
        //----------------------------------------------------------------------
        /**
        Encode input with mask detection.
        Shorthand for encodeMask, without specifing particular, static mask number.
        
        @param QRinput $input data object to be encoded
        @return 
        */
        public function encodeInput(QRinput $input)
        {
            return $this->encodeMask($input, -1);
        }
        
        //----------------------------------------------------------------------
        /**
        Encode string, forcing 8-bit encoding
        @param String $string input string
        @param Integer $version code version (size of code area)
        @param Integer $level ECC level (see: Global Constants -> Levels of Error Correction)
        @return QRcode $this (current instance)
        */
        public function encodeString8bit($string, $version, $level)
        {
            if($string == NULL) {
                throw new Exception('empty string!');
                return NULL;
            }

            $input = new QRinput($version, $level);
            if($input == NULL) return NULL;

            $ret = $input->append(QR_MODE_8, strlen($string), str_split($string));
            if($ret < 0) {
                unset($input);
                return NULL;
            }
            return $this->encodeInput($input);
        }

        //----------------------------------------------------------------------
        /**
        Encode string, using optimal encodings.
        Encode string dynamically adjusting encoding for subsections of string to
        minimize resulting code size. For complex string it will split string into
        subsections: Numerical, Alphanumerical or 8-bit.
        @param String $string input string
        @param Integer $version code version (size of code area)
        @param String $level ECC level (see: Global Constants -> Levels of Error Correction)
        @param Integer $hint __QR_MODE_8__ or __QR_MODE_KANJI__, Because Kanji encoding
        is kind of 8 bit encoding we need to hint encoder to use Kanji mode explicite.
        (otherwise it may try to encode it as plain 8 bit stream)
        @param Boolean $casesensitive hint if given string is case-sensitive, because
        if not - encoder may use optimal QR_MODE_AN instead of QR_MODE_8
        @return QRcode $this (current instance)
        */
        public function encodeString($string, $version, $level, $hint, $casesensitive)
        {

            if($hint != QR_MODE_8 && $hint != QR_MODE_KANJI) {
                throw new Exception('bad hint');
                return NULL;
            }

            $input = new QRinput($version, $level);
            if($input == NULL) return NULL;

            $ret = QRsplit::splitStringToQRinput($string, $input, $hint, $casesensitive);
            if($ret < 0) {
                return NULL;
            }

            return $this->encodeInput($input);
        }
        
        //######################################################################
        /**
        Creates PNG image containing QR-Code.
        Simple helper function to create QR-Code Png image with one static call.
        @param String $text text string to encode 
        @param String $outfile (optional) output file name, if __false__ outputs to browser with required headers
        @param Integer $level (optional) error correction level __QR_ECLEVEL_L__, __QR_ECLEVEL_M__, __QR_ECLEVEL_Q__ or __QR_ECLEVEL_H__
        @param Integer $size (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $margin (optional) code margin (silent zone) in 'virtual'  pixels
        @param Boolean $saveandprint (optional) if __true__ code is outputed to browser and saved to file, otherwise only saved to file. It is effective only if $outfile is specified.
        */
        
        public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false) 
        {
            $enc = QRencode::factory($level, $size, $margin);
            return $enc->encodePNG($text, $outfile, $saveandprint=false);
        }

        //----------------------------------------------------------------------
        /**
        Creates text (1's & 0's) containing QR-Code.
        Simple helper function to create QR-Code text with one static call.
        @param String $text text string to encode 
        @param String $outfile (optional) output file name, when __false__ file is not saved
        @param Integer $level (optional) error correction level __QR_ECLEVEL_L__, __QR_ECLEVEL_M__, __QR_ECLEVEL_Q__ or __QR_ECLEVEL_H__
        @param Integer $size (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $margin (optional) code margin (silent zone) in 'virtual'  pixels
        @return Array containing line of code with 1 and 0 for every code line
        */
        
        public static function text($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4) 
        {
            $enc = QRencode::factory($level, $size, $margin);
            return $enc->encode($text, $outfile);
        }

        //----------------------------------------------------------------------
        /**
        Creates Raw Array containing QR-Code.
        Simple helper function to create QR-Code array with one static call.
        @param String $text text string to encode 
        @param Boolean $outfile (optional) not used, shuold be __false__
        @param Integer $level (optional) error correction level __QR_ECLEVEL_L__, __QR_ECLEVEL_M__, __QR_ECLEVEL_Q__ or __QR_ECLEVEL_H__
        @param Integer $size (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $margin (optional) code margin (silent zone) in 'virtual'  pixels
        @return Array containing Raw QR code
        */
        
        public static function raw($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4) 
        {
            $enc = QRencode::factory($level, $size, $margin);
            return $enc->encodeRAW($text, $outfile);
        }
        
        //----------------------------------------------------------------------
        /**
        Creates Html+JS code to draw  QR-Code with HTML5 Canvas.
        Simple helper function to create QR-Code array with one static call.
        @param String $text text string to encode 
        @param String $elemId (optional) target Canvas tag id attribute, if __false__ Canvas tag with auto id will be created 
        @param Integer $level (optional) error correction level __QR_ECLEVEL_L__, __QR_ECLEVEL_M__, __QR_ECLEVEL_Q__ or __QR_ECLEVEL_H__
        @param Integer $width (optional) CANVAS element width (sam as height)
        @param Integer $size (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $margin (optional) code margin (silent zone) in 'virtual'  pixels
        @param Boolean $autoInclude (optional) if __true__, required qrcanvas.js lib will be included (only once)
        @return String containing JavaScript creating the code, Canvas element (when $elemId is __false__) and script tag with required lib (when $autoInclude is __true__ and not yet included)
        */
        
        public static function canvas($text, $elemId = false, $level = QR_ECLEVEL_L, $width = false, $size = false, $margin = 4, $autoInclude = false) 
        {
            $html = '';
            $extra = '';
            
            if ($autoInclude) {
                if (!self::$jscanvasincluded) {
                    self::$jscanvasincluded = true;
                    echo '<script type="text/javascript" src="qrcanvas.js"></script>';
                }
            }
            
            $enc = QRencode::factory($level, 1, 0);
            $tab_src = $enc->encode($text, false);
            $area = new QRcanvasOutput($tab_src);
            $area->detectGroups();
            $area->detectAreas();
            
            if ($elemId === false) {
                $elemId = 'qrcode-'.md5(mt_rand(1000,1000000).'.'.mt_rand(1000,1000000).'.'.mt_rand(1000,1000000).'.'.mt_rand(1000,1000000));
                
                if ($width == false) {
                    if (($size !== false) && ($size > 0))  {
                        $width = ($area->getWidth()+(2*$margin)) * $size;
                    } else {
                        $width = ($area->getWidth()+(2*$margin)) * 4;
                    }
                }
                
                $html .= '<canvas id="'.$elemId.'" width="'.$width.'" height="'.$width.'">Your browser does not support CANVAS tag! Please upgrade to modern version of FireFox, Opera, Chrome or Safari/Webkit based browser</canvas>';
            }
            
            if ($width !== false) {
                $extra .= ', '.$width.', '.$width;
            } 
                
            if ($margin !== false) {
                $extra .= ', '.$margin.', '.$margin;                
            }
            
            $html .= '<script>if(eval("typeof "+\'QRdrawCode\'+"==\'function\'")){QRdrawCode(QRdecompactOps(\''.$area->getCanvasOps().'\')'."\n".', \''.$elemId.'\', '.$area->getWidth().' '.$extra.');}else{alert(\'Please include qrcanvas.js!\');}</script>';
            
            return $html;
        }
        
        //----------------------------------------------------------------------
        /**
        Creates SVG with QR-Code.
        Simple helper function to create QR-Code SVG with one static call.
        @param String $text text string to encode 
        @param Boolean $elemId (optional) target SVG tag id attribute, if __false__ SVG tag with auto id will be created 
        @param String $outfile (optional) output file name, when __false__ file is not saved
        @param Integer $level (optional) error correction level __QR_ECLEVEL_L__, __QR_ECLEVEL_M__, __QR_ECLEVEL_Q__ or __QR_ECLEVEL_H__
        @param Integer $width (optional) SVG element width (sam as height)
        @param Integer $size (optional) pixel size, multiplier for each 'virtual' pixel
        @param Integer $margin (optional) code margin (silent zone) in 'virtual'  pixels
        @param Boolean $compress (optional) if __true__, compressed SVGZ (instead plaintext SVG) is saved to file
        @return String containing SVG tag
        */
        
        public static function svg($text, $elemId = false, $outFile = false, $level = QR_ECLEVEL_L, $width = false, $size = false, $margin = 4, $compress = false) 
        {
            $enc = QRencode::factory($level, 1, 0);
            $tab_src = $enc->encode($text, false);
            $area = new QRsvgOutput($tab_src);
            $area->detectGroups();
            $area->detectAreas();
            
            if ($elemId === false) {
                $elemId = 'qrcode-'.md5(mt_rand(1000,1000000).'.'.mt_rand(1000,1000000).'.'.mt_rand(1000,1000000).'.'.mt_rand(1000,1000000));
                
                if ($width == false) {
                    if (($size !== false) && ($size > 0))  {
                        $width = ($area->getWidth()+(2*$margin)) * $size;
                    } else {
                        $width = ($area->getWidth()+(2*$margin)) * 4;
                    }
                }
            }
            
            $svg = '<svg xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            version="1.1"
            baseProfile="full"
            viewBox="'.(-$margin).' '.(-$margin).' '.($area->getWidth()+($margin*2)).' '.($area->getWidth()+($margin*2)).'" 
            width="'.$width.'"
            height="'.$width.'"
            id="'.$elemId.'">'."\n";
   
            $svg .= $area->getRawSvg().'</svg>';
   
            if ($outFile !== false) {
                $xmlPreamble = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'."\n";
                $svgContent = $xmlPreamble.$svg;
                
                if ($compress === true) {
                    file_put_contents($outFile, gzencode($svgContent));
                } else {
                    file_put_contents($outFile, $svgContent);
                }
            }
            
            return $svg;
        }
    }
    
    //##########################################################################
    /** Fills frame with data.
    Each empty frame consist of markers, timing symbols and format configuration.
    Remaining place is place for data, and should be filled according to QR Code spec.
    */
    class QRframeFiller {
    
        public $width; ///< __Integer__ Frame width
        public $frame; ///< __Array__ Frame itself
        public $x;     ///< __Integer__ current X position
        public $y;     ///< __Integer__ current Y position
        public $dir;   ///< __Integer__ direction
        public $bit;   ///< __Integer__ bit
        
        //----------------------------------------------------------------------
        /** Frame filler Constructor.
        @param Integer $width frame size
        @param Array $frame Frame array
        */
        public function __construct($width, &$frame)
        {
            $this->width = $width;
            $this->frame = $frame;
            $this->x = $width - 1;
            $this->y = $width - 1;
            $this->dir = -1;
            $this->bit = -1;
        }
        
        //----------------------------------------------------------------------
        /** Sets frame code at given position.
        @param Array $at position, map containing __x__ and __y__ coordinates
        @param Integer $val value to set
        */
        public function setFrameAt($at, $val)
        {
            $this->frame[$at['y']][$at['x']] = chr($val);
        }
        
        //----------------------------------------------------------------------
        /** Gets frame code from given position.
        @param Array $at position, map containing __x__ and __y__ coordinates
        @return Integer value at requested position
        */
        public function getFrameAt($at)
        {
            return ord($this->frame[$at['y']][$at['x']]);
        }
        
        //----------------------------------------------------------------------
        /** Proceed to next code point. */
        public function next()
        {
            do {
            
                if($this->bit == -1) {
                    $this->bit = 0;
                    return array('x'=>$this->x, 'y'=>$this->y);
                }

                $x = $this->x;
                $y = $this->y;
                $w = $this->width;

                if($this->bit == 0) {
                    $x--;
                    $this->bit++;
                } else {
                    $x++;
                    $y += $this->dir;
                    $this->bit--;
                }

                if($this->dir < 0) {
                    if($y < 0) {
                        $y = 0;
                        $x -= 2;
                        $this->dir = 1;
                        if($x == 6) {
                            $x--;
                            $y = 9;
                        }
                    }
                } else {
                    if($y == $w) {
                        $y = $w - 1;
                        $x -= 2;
                        $this->dir = -1;
                        if($x == 6) {
                            $x--;
                            $y -= 8;
                        }
                    }
                }
                if($x < 0 || $y < 0) return null;

                $this->x = $x;
                $this->y = $y;

            } while(ord($this->frame[$y][$x]) & 0x80);
                        
            return array('x'=>$x, 'y'=>$y);
        }
        
    } ;
    
    //##########################################################################    
    /** QR Code encoder.
    Encoder is used by QRCode to create simple static code generators. */
    class QRencode {
    
        public $casesensitive = true; ///< __Boolean__ does input stream id case sensitive, if not encoder may use more optimal charsets
        public $eightbit = false;     ///< __Boolean__ does input stream is 8 bit
        
        public $version = 0;          ///< __Integer__ code version (total size) if __0__ - will be auto-detected
        public $size = 3;             ///< __Integer__ pixel zoom factor, multiplier to map virtual code pixels to image output pixels
        public $margin = 4;           ///< __Integer__ margin (silent zone) size, in code pixels
        
        public $structured = 0;       ///< Structured QR codes. Not supported.
        
        public $level = QR_ECLEVEL_L; ///< __Integer__ error correction level __QR_ECLEVEL_L__, __QR_ECLEVEL_M__, __QR_ECLEVEL_Q__ or __QR_ECLEVEL_H__
        public $hint = QR_MODE_8;     ///< __Integer__ encoding hint, __QR_MODE_8__ or __QR_MODE_KANJI__, Because Kanji encoding is kind of 8 bit encoding we need to hint encoder to use Kanji mode explicite. (otherwise it may try to encode it as plain 8 bit stream)
        
        //----------------------------------------------------------------------
        /** Encoder instances factory.
        @param Integer $level error correction level __QR_ECLEVEL_L__, __QR_ECLEVEL_M__, __QR_ECLEVEL_Q__ or __QR_ECLEVEL_H__
        @param Integer $size pixel zoom factor, multiplier to map virtual code pixels to image output pixels
        @param Integer $margin margin (silent zone) size, in code pixels
        @return builded QRencode instance
        */
        public static function factory($level = QR_ECLEVEL_L, $size = 3, $margin = 4)
        {
            $enc = new QRencode();
            $enc->size = $size;
            $enc->margin = $margin;
            
            switch ($level.'') {
                case '0':
                case '1':
                case '2':
                case '3':
                        $enc->level = $level;
                    break;
                case 'l':
                case 'L':
                        $enc->level = QR_ECLEVEL_L;
                    break;
                case 'm':
                case 'M':
                        $enc->level = QR_ECLEVEL_M;
                    break;
                case 'q':
                case 'Q':
                        $enc->level = QR_ECLEVEL_Q;
                    break;
                case 'h':
                case 'H':
                        $enc->level = QR_ECLEVEL_H;
                    break;
            }
            
            return $enc;
        }
        
        //----------------------------------------------------------------------
        /** Encodes input into Raw code table.
        @param String $intext input text
        @param Boolean $notused (optional, not used) placeholder for similar outfile parameter
        @return __Array__ Raw code frame
        */
        public function encodeRAW($intext, $notused = false) 
        {
            $code = new QRcode();

            if($this->eightbit) {
                $code->encodeString8bit($intext, $this->version, $this->level);
            } else {
                $code->encodeString($intext, $this->version, $this->level, $this->hint, $this->casesensitive);
            }
            
            return $code->data;
        }

        //----------------------------------------------------------------------
        /** Encodes input into binary code table.
        @param String $intext input text
        @param String $outfile (optional) output file to save code table, if __false__ file will be not saved
        @return __Array__ binary code frame
        */
        public function encode($intext, $outfile = false) 
        {
            $code = new QRcode();

            if($this->eightbit) {
                $code->encodeString8bit($intext, $this->version, $this->level);
            } else {
                $code->encodeString($intext, $this->version, $this->level, $this->hint, $this->casesensitive);
            }
            
            QRtools::markTime('after_encode');
            
            $binarized = QRtools::binarize($code->data);
            if ($outfile!== false) {
                file_put_contents($outfile, join("\n", $binarized));
            }
            
            return $binarized;
        }
        
        //----------------------------------------------------------------------
        /** Encodes input into PNG image.
        @param String $intext input text
        @param String $outfile (optional) output file name, if __false__ outputs to browser with required headers
        @param Boolean $saveandprint (optional) if __true__ code is outputed to browser and saved to file, otherwise only saved to file. It is effective only if $outfile is specified.
        */
        public function encodePNG($intext, $outfile = false, $saveandprint=false) 
        {
            try {
            
                ob_start();
                $tab = $this->encode($intext);
                $err = ob_get_contents();
                ob_end_clean();
                
                if ($err != '')
                    QRtools::log($outfile, $err);
                
                $maxSize = (int)(QR_PNG_MAXIMUM_SIZE / (count($tab)+2*$this->margin));
                
                QRimage::png($tab, $outfile, min(max(1, $this->size), $maxSize), $this->margin,$saveandprint);
            
            } catch (Exception $e) {
            
                QRtools::log($outfile, $e->getMessage());
            
            }
        }
    }

    /** @}*/
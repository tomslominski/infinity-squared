<?php

/*
 * PHP QR Code encoder
 *
 * Common constants
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
 

    /** \defgroup QR_CONST Global Constants
    Constant used globally for function arguments.
    Make PHP calls a little bit more clear, in place of missing (in dynamicaly typed language) enum types.
    * @{ 
    */
     
    /** @name QR-Code Encoding Modes */
    /** @{ */
    
    /** null encoding, used when no encoding was speciffied yet */
    define('QR_MODE_NUL', -1);   
    /** Numerical encoding, only numbers (0-9) */	
    define('QR_MODE_NUM', 0);   
    /** AlphaNumerical encoding, numbers (0-9) uppercase text (A-Z) and few special characters (space, $, %, *, +, -, ., /, :) */    
    define('QR_MODE_AN', 1);  
    /** 8-bit encoding, raw 8 bit encoding */
    define('QR_MODE_8', 2);    
    /** Kanji encoding */	
    define('QR_MODE_KANJI', 3);    
    /** Structure, internal encoding for structure-related data */	
    define('QR_MODE_STRUCTURE', 4); 
    /**@}*/

    /** @name QR-Code Levels of Error Correction 
    Constants speciffy ECC level from lowest __L__ to the highest __H__. 
    Higher levels are recomended for Outdoor-presented codes, but generates bigger codes.
    */
    /** @{*/
	/** ~7% of codewords can be restored */
    define('QR_ECLEVEL_L', 0); 
	/** ~15% of codewords can be restored */
    define('QR_ECLEVEL_M', 1); 
	/** ~25% of codewords can be restored */
    define('QR_ECLEVEL_Q', 2);
	/** ~30% of codewords can be restored */
    define('QR_ECLEVEL_H', 3);
    /** @}*/
   
    /** @name QR-Code Supported output formats */
    /** @{*/
    define('QR_FORMAT_TEXT', 0);
    define('QR_FORMAT_PNG',  1);
    /** @}*/
    
    /** @}*/

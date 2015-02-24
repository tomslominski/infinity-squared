<?php
/*
 * PHP QR Code encoder
 *
 * CANVAS output
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
 
    /** @addtogroup OutputGroup */
    /** @{ */
    
    class QRcanvasOutput extends QRarea {
 
        public function __construct($source_tab) 
        {
            parent::__construct($source_tab);
        }
 
        //----------------------------------------------------------------------
        public static function encodeNum($num)
        {
            $addTab = array(0=>'', 1=>'z', 2=>'Z', 3=>'+');
            $map = '0123456789abcdefghijklmnopqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXY';
            $mapPos = $num % 60;
            $mapAdd = (int)($num / 60);
            
            return $addTab[$mapAdd].$map[$mapPos];
        }
        
        //----------------------------------------------------------------------
        public static function compact_path(&$pathTab) 
        {
            if (count($pathTab) == 0) {
                $pathTab = '';
            } else {
                $pathTab = count($pathTab).','.join(',', $pathTab);
            }
        }
        
        //----------------------------------------------------------------------
        public static function compact_points(&$pointsTab) 
        {
            if (count($pointsTab) == 0) {
                $pointsTab = '';
            } else {
                $compacted = '';
                foreach ($pointsTab as $point)
                    $compacted .= self::encodeNum($point);
                $pointsTab = $compacted;
            }
        }
        
        //----------------------------------------------------------------------
        public static function compactCanvasCommands($ops)
        {
            $accumulated  = array();
            
            $accumulated['SR'] = array();
            $accumulated['WR'] = array();
            $accumulated['SP'] = array();
            $accumulated['WP'] = array();
            $accumulated['SB'] = array();
            $accumulated['WB'] = array();
            $accumulated['SO'] = array();
            
            while (count($ops) > 0) {
                $color  = array_shift($ops);
                $opcode = array_shift($ops);
                
                if (($opcode == 'R') || ($opcode == 'P')) {
                
                    do {
                        $num = array_shift($ops);
                        if (is_int($num)) {
                            $accumulated[$color.$opcode][] = $num;
                        } else {
                            array_unshift($ops, $num);
                        }
                        
                    } while ((count($ops) > 0)&&(is_int($num)));

                    
                } else if ($opcode == 'B') {
                    
                    array_shift($ops);
                    
                    $px  = array_shift($ops);
                    $py  = array_shift($ops);
                    
                    array_shift($ops);
                    
                    $conftab = array();
                    $num = array_shift($ops);
                    
                    while ((count($ops) > 0)&&(!($num === 'E'))) {
                        $conftab[] = $num;
                        $num = array_shift($ops);
                    }
                    
                    $cc = count($conftab);
                    $deltas = '';
                    
                    $lastposx = $px;
                    $lastposy = $py;
                    
                    for($pos=0;$pos <$cc; $pos+=2) {
                    
                        $dx = $lastposx - $conftab[$pos];
                        $dy = $lastposy - $conftab[$pos+1];
                    
                        $lastposx = $conftab[$pos];
                        $lastposy = $conftab[$pos+1];
                    
                        if ($dx < 0) {
                            $deltas .= chr(ord('a')-1-$dx);
                        } else if ($dx > 0) {
                            $deltas .= chr(ord('A')-1+$dx);
                        } else {
                            $deltas .= '0';
                        }
                        
                        if ($dy < 0) {
                            $deltas .= chr(ord('a')-1-$dy);
                        } else if ($dy > 0) {
                            $deltas .= chr(ord('A')-1+$dy);
                        } else {
                            $deltas .= '0';
                        }
                    
                    }
                    
                    $deltas = strtr($deltas, array(
                        '00'=>'1',
                        'aa'=>'2',
                        'aA'=>'3',
                        'Aa'=>'4',
                        'AA'=>'5',
                        'aB'=>'6',
                        'Ab'=>'7',
                        'bA'=>'8',
                        'Ba'=>'9'
                    ));
                    
                    $accumulated[$color.$opcode][] = join(',', array($px, $py, $deltas));
                } else if ($opcode == 'O') {
                    $px  = array_shift($ops);
                    $py  = array_shift($ops);
                    
                    $accumulated[$color.$opcode][] = join(',', array($px, $py));
                }
            }
            
            self::compact_points($accumulated['SR']);
            self::compact_points($accumulated['WR']);
            self::compact_points($accumulated['SP']);
            self::compact_points($accumulated['WP']);
            
            self::compact_path($accumulated['SB']);
            self::compact_path($accumulated['WB']);
            
            if (count($accumulated['SO']) > 0)
                    $accumulated['SO'] = join(',',$accumulated['SO']);
            else    $accumulated['SO'] = '';
            
            $mapping = array(
                'SO'=>'O',
                'SB'=>'B',
                'WB'=>'b',
                'SR'=>'R',
                'WR'=>'r',
                'SP'=>'P',
                'WP'=>'p'
            );
            
            $whole = array();
            
            foreach($mapping as $key=>$symb) {
                if ($accumulated[$key]!='')
                    $whole[] = $symb.','.$accumulated[$key];
            }
            
            return join(',', $whole);
        }
        

        //----------------------------------------------------------------------
        public function getCanvasOps()
        {
            $ops = array();
            
            foreach ($this->paths as $path) {
                switch ($path[0]) {
                    case QR_AREA_PATH:
                            $pNum = 0;
                            
                            foreach($path[1] as $pathDetails) {
                                if ($pNum == 0) {
                                    $ops[] = 'S';
                                } else if ($pNum > 0) {
                                    $ops[] = 'W';
                                }
                                
                                $ops[] = 'B';
                                
                                $px = array_shift($pathDetails);
                                $py = array_shift($pathDetails);
                                
                                $ops[] = 'M';
                                $ops[] = $px;
                                $ops[] = $py;
                                
                                $rle_steps = array_shift($pathDetails);
                                
                                $lastOp = '';
                                
                                while(count($rle_steps) > 0) {
                                
                                    $delta = 1;
                                    
                                    $operator = array_shift($rle_steps);
                                    if (($operator != 'R') && ($operator != 'L') && ($operator != 'T') && ($operator != 'B')) {
                                        $delta = (int)$operator;
                                        $operator = array_shift($rle_steps);
                                    }
                                    
                                    if ($operator == 'R') $px += $delta;
                                    if ($operator == 'L') $px -= $delta;
                                    if ($operator == 'T') $py -= $delta;
                                    if ($operator == 'B') $py += $delta;
                                    
                                    if ($lastOp != 'T')
                                        $ops[] = 'T';
                                        
                                    $ops[] = $px;
                                    $ops[] = $py;
                                    
                                    $lastOp = 'T';
                                }
                                
                                $ops[] = 'E';
                                
                                $pNum++;
                            }
    
                        break;
                    case QR_AREA_POINT:
                                
                                $symb = array_shift($path);
                                
                                $ops[] = 'S';
                                
                                $lastOp = '';
                                
                                while(count($path) > 0) {
                                    $px = array_shift($path);
                                    $py = array_shift($path);
                                    
                                    if ($lastOp != 'P')
                                        $ops[] = 'P';
                                        
                                    $ops[] = $px;
                                    $ops[] = $py;
                                    
                                    $lastOp = 'P';
                                }
                                
                        break;
                        
                    case QR_AREA_RECT:
                                
                                $symb = array_shift($path);
                                
                                $ops[] = 'S';
                                
                                $lastOp = '';
                                
                                while(count($path) > 0) {
                                    $px = array_shift($path);
                                    $py = array_shift($path);
                                    $ex = array_shift($path);
                                    $ey = array_shift($path);
                                    
                                    if ($lastOp != 'R')
                                        $ops[] = 'R';
                                        
                                    $ops[] = $px;
                                    $ops[] = $py;
                                    $ops[] = $ex-$px;
                                    $ops[] = $ey-$py;
                                    
                                    $lastOp = 'R';
                                }
                                
                        break;                      
                        
                    case QR_AREA_LSHAPE:
                                
                                $symb = array_shift($path);
                                
                                while(count($path) > 0) {
                                    $px = array_shift($path);
                                    $py = array_shift($path);
                                    $mode = (int)array_shift($path);
                                    
                                    $pxd = ($mode % 2)?1:0;
                                    $pyd = ($mode > 1)?1:0;
                                    
                                    $ops[] = 'S';
                                    $ops[] = 'R';
                                    $ops[] = $px;
                                    $ops[] = $py;
                                    $ops[] = 2;
                                    $ops[] = 2;
                                    
                                    $ops[] = 'W';
                                    $ops[] = 'P';
                                    $ops[] = $px+$pxd;
                                    $ops[] = $py+$pyd;
                                }
                                
                        break;  
                        
                    case QR_AREA_TRACKER:
                                
                                $symb = array_shift($path);
                                
                                $px = array_shift($path);
                                $py = array_shift($path);
                                    
                                $ops[] = 'S';
                                $ops[] = 'O';
                                $ops[] = $px;
                                $ops[] = $py;
                                    
                        break;  
                }
            }
            
            return self::compactCanvasCommands($ops);
        }
    }
    
    /** @} */
 
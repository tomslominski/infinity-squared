<?php
/*
 * PHP QR Code encoder
 *
 * Area finding for SVG and CANVAS output
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

    //      N
    //    W  E
    //     S
    
    define('QR_AREA_N', 0);
    define('QR_AREA_E', 1);
    define('QR_AREA_S', 2);
    define('QR_AREA_W', 3);
    
    define('QR_AREA_X', 0);
    define('QR_AREA_Y', 1);
    
    define('QR_AREA_TRACKER', 0);
    define('QR_AREA_PATH',    1);
    define('QR_AREA_POINT',   2);
    define('QR_AREA_RECT',    3);
    define('QR_AREA_LSHAPE',  4);
    
    /** @addtogroup OutputGroup */
    /** @{ */
    
    class QRareaGroup {
        public $total = 0;
        public $vertical = false;
        public $horizontal = false;
        public $points = array();
        public $id = 0;
        public $paths = array();
        
        //----------------------------------------------------------------------
        public function __construct($selfId, $sx, $sy)
        {
            $this->total = 1;
            $this->points = array(array($sx,$sy,false));
            $this->id = $selfId;
        }
        
    }

    //##########################################################################
    
    class QRarea {
    
        public    $width = 0;
        private   $tab = array();
        private   $tab_edges = array();
        private   $groups = array();
        private   $curr_group = 0;
        public    $paths = array();
    
    
        //----------------------------------------------------------------------
        public function __construct($source_tab) 
        {
            $py = 0;
            $this->width = count($source_tab);
            $this->tab = array();
            $this->tab_edges = array();
            $this->paths = array();
    
            foreach ($source_tab as $line) {
                $arr = array();
                $arr_edge = array();
                $px=0;
                
                foreach (str_split($line) as $item) {
                    
                    if ($py<7 && $px<7)
                        $item = 0;
                        
                    if ($py<7 && $px>=($this->width-7))
                        $item = 0;
                        
                    if ($py>=($this->width-7) && $px<7)
                        $item = 0;
                                    
                    $arr[] = (int)$item;
                    $arr_edge[] = array(false, false, false, false);
                    
                    $px++;
                }
                
                $this->tab[] = $arr;
                $this->tab_edges[] = $arr_edge;
                $py++;
            }
            
            $this->paths[] = array(QR_AREA_TRACKER, 0,0);
            $this->paths[] = array(QR_AREA_TRACKER, 0,($this->width-7));
            $this->paths[] = array(QR_AREA_TRACKER, ($this->width-7),0);
            
            $this->groups = array();
            $this->curr_group = 1;
        }
    
        //----------------------------------------------------------------------
        public function getGroups() 
        {
            return $this->groups;
        }
        
        //----------------------------------------------------------------------
        public function getPaths() 
        {
            return $this->paths;
        }
        
        //----------------------------------------------------------------------
        public function getWidth() 
        {
            return $this->width;
        }
        
        //----------------------------------------------------------------------
        public function dumpTab() 
        {
            echo "<style>";
            echo "td { height: 2.5em;  color: black; font-size: 8px;
            border-top: 1px solid silver; border-left: 1px solid silver }";
            echo "table { border-bottom: 1px solid silver; border-right: 1px solid silver }";
            echo "</style>";
            echo "<table border=0 cellpadding=0 cellspacing=0>";
            
            $colorTab = array();
            
            foreach($this->tab as $line) {
                foreach($line as $item) {
                    if (!isset($colorTab[$item])) {
                        $colorTab[$item] = 'hsl('.mt_rand(0, 360).', '.floor((mt_rand(0, 25))+75).'%, 50%)';
                    }
                }
            }
            
            foreach($this->tab as $line) {
                echo "<tr>";
                foreach($line as $item) {
                    if ($item == 0) {
                        echo "<td>&nbsp;</td>";
                    } else {
                        echo "<td style='text-align:center;width: 4em;background:".$colorTab[$item]."'>".$item."</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        
        //----------------------------------------------------------------------
        public function dumpEdges() 
        {
            $style_off = '1px dotted silver;';
            $style_on = '3px solid red;';
            
            $colorAlloc = array();
            
            echo "<table border='0'>";
            $py = 0;
            foreach($this->tab_edges as $line) {
                $px = 0;
                echo "<tr>";
                foreach($line as $item) {
                    
                    $styles = 'border-top:';
                    if ($item[QR_AREA_N])
                            $styles .=  $style_on;
                    else    $styles .=  $style_off;
                    
                    $styles .= 'border-bottom:';
                    if ($item[QR_AREA_S])
                            $styles .=  $style_on;
                    else    $styles .=  $style_off;
                    
                    $styles .= 'border-right:';
                    if ($item[QR_AREA_E])
                            $styles .=  $style_on;
                    else    $styles .=  $style_off;
                    
                    $styles .= 'border-left:';
                    if ($item[QR_AREA_W])
                            $styles .=  $style_on;
                    else    $styles .=  $style_off;
                    
                    $color = '';
                    $grp = $this->tab[$py][$px];
                    
                    if ($grp>0) {
                        if (!isset($colorAlloc[$grp])) {
                            $colorAlloc[$grp] = 'hsl('.mt_rand(0, 360).', '.floor((mt_rand(0, 25))+75).'%, 50%)';
                        }
                
                        $color = 'background:'.$colorAlloc[$grp];
                    }
                    
                    if ($grp == 0)
                        $grp = '&nbsp;';
                    
                    echo "<td style='text-align:center;width:1.5em;".$styles.$color."'>".$grp."</td>";
                    $px++;
                }
                echo "</tr>";
                $py++;
            }
            echo "</table>";
        }
        
        //----------------------------------------------------------------------
        private static function rle(&$stringData)
        {
            $outArray = array();
            $symbolArray = str_split($stringData);
            $last = '';
            $run = 1;
            
            while (count($symbolArray) > 0) {
                $symbol = array_shift($symbolArray);
                
                if ($symbol != $last) {
                    if ($run > 1) 
                        $outArray[] = $run;
                    
                    if ($last != '')
                        $outArray[] = $last;
                        
                    $run = 1;
                    $last = $symbol;
                } else {
                    $run++;
                }
            }
            
            if ($run > 1) 
                $outArray[] = $run;
                
            $outArray[] = $last;
            
            $stringData = $outArray;
        }
        
    
        //----------------------------------------------------------------------
        private function getAt($posx, $posy)
        {
            if (($posx<0)||($posy<0)||($posx>=$this->width)||($posy>=$this->width))
                return 0;           
                
            return $this->tab[$posy][$posx];            
        }
        
        //----------------------------------------------------------------------
        private function getOnElem($elem, $deltax = 0, $deltay = 0)
        {
            $posx = $elem[0]+$deltax;
            $posy = $elem[1]+$deltay;
            
            if (($posx<0)||($posy<0)||($posx>=$this->width)||($posy>=$this->width))
                return 0;           
                
            return $this->tab[$posy][$posx];            
        }
        
        //----------------------------------------------------------------------
        private function addGroupElement($groupId, $h, $v, $sx, $sy)
        {
            $this->groups[$groupId]->total++;
            if ($h)
                $this->groups[$groupId]->horizontal = true;
            if ($v)
                $this->groups[$groupId]->vertical = true;                
            $this->groups[$groupId]->points[] = array($sx, $sy, false);
        }
        
        //----------------------------------------------------------------------
        public function detectGroups()
        {
            for ($sy = 0; $sy < $this->width; $sy++) {
                for ($sx = 0; $sx < $this->width; $sx++) {
                
                    if ($this->tab[$sy][$sx] == 1) { // non-allocated
                    
                        $gid_left = 0;
                        $gid_top = 0;
                    
                        $grouped = false;
                        
                        if ($sx>0) {
                            
                            $gid_left = $this->tab[$sy][$sx-1]; // previous on left
                            
                            if ($gid_left > 1) { // if already in group
                                $this->tab[$sy][$sx] = $gid_left;
                                $grouped = true;
                                $this->addGroupElement($gid_left, true, false, $sx, $sy);
                            }  
                        }
                        
                        if ($sy > 0) {
                        
                            $gid_top = $this->tab[$sy-1][$sx]; // previous on top
                            
                            if ($gid_top > 1) { //if in group
                                if (!$grouped) { // and not grouped
                                
                                    $this->tab[$sy][$sx] = $gid_top;
                                    $grouped = true;
                                    
                                    $this->addGroupElement($gid_top, false, true, $sx, $sy);
                                    
                                } else if($gid_top != $gid_left) { // was in left group
                                
                                    $grouped = true;
                                    
                                    $this->groups[$gid_top]->vertical = true;
                                    $this->groups[$gid_top]->horizontal = true;
                                    
                                    $this->groups[$gid_top]->total = $this->groups[$gid_top]->total + $this->groups[$gid_left]->total;
                                    
                                    foreach($this->groups[$gid_left]->points as $elem)
                                        $this->tab[$elem[1]][$elem[0]] = $gid_top;
                                    
                                    $this->groups[$gid_top]->points = array_values(array_merge($this->groups[$gid_top]->points, $this->groups[$gid_left]->points));
                                    unset($this->groups[$gid_left]);
                                    
                                    //refarb group
                                }
                            }
                        }
                        
                        if (!$grouped) {
                            $this->curr_group++;
                            $this->tab[$sy][$sx] = $this->curr_group;
                            $this->groups[$this->curr_group] = new QRareaGroup($this->curr_group, $sx, $sy);
                        }
                        
                    }
                }
            }
        }
        
        //----------------------------------------------------------------------
        private function detectSquare($group)
        {
            $max_x = 0;
            $max_y = 0;
            $min_x = $this->width;
            $min_y = $this->width;
            
            foreach($group->points as $elem) {
                $min_x = min($min_x, $elem[QR_AREA_X]);
                $max_x = max($max_x, $elem[QR_AREA_X]);
                $min_y = min($min_y, $elem[QR_AREA_Y]);
                $max_y = max($max_y, $elem[QR_AREA_Y]);
            }
            
            return array($min_x, $min_y, $max_x+1, $max_y+1);
        }
        
        //----------------------------------------------------------------------
        public function detectAreas()
        {
            $squares = array();
            $points = array();
            $lshapes = array();
            
            foreach ($this->groups as $groupId=>&$group) {
                if ($group->total > 3) {
                
                    if ((!$group->vertical)||(!$group->horizontal)) {
                    
                        $squareCoord = $this->detectSquare($group);
                        array_unshift($squareCoord, QR_AREA_RECT);
                        
                        $this->paths[] = $squareCoord;
                    
                    } else {
                
                        $this->detectPaths($group);
                        unset($group->points);
                        
                        foreach($group->paths as &$path)
                            self::rle($path[2]);
                        
                        $this->paths[] = array(QR_AREA_PATH, $group->paths);
                    }
                } else if (($group->total == 3)&&($group->vertical)&&($group->horizontal)) {
                    $squareCoord = $this->detectSquare($group);
                    $variant = 0;
                    
                    if ($this->getOnElem($squareCoord, 0, 0) != $group->id)
                        $variant = 0;
                        
                    if ($this->getOnElem($squareCoord, 1, 0) != $group->id)
                        $variant = 1;
                        
                    if ($this->getOnElem($squareCoord, 0, 1) != $group->id)
                        $variant = 2;
                        
                    if ($this->getOnElem($squareCoord, 1, 1) != $group->id)
                        $variant = 3;
                        
                    $lshapes[] = $squareCoord[QR_AREA_X];
                    $lshapes[] = $squareCoord[QR_AREA_Y];
                    $lshapes[] = $variant;
                    
                } else if ($group->total >= 2) {
                    $squareCoord = $this->detectSquare($group);
                    $squares = array_merge($squares, $squareCoord);
                } else if ($group->total == 1) {
                    $points[] = $group->points[0][0];
                    $points[] = $group->points[0][1];
                }
            }
            
            if (count($points) > 0) {
                array_unshift($points, QR_AREA_POINT);
                $this->paths[] = $points;
            }
            
            if (count($squares) > 0) {
                array_unshift($squares, QR_AREA_RECT);
                $this->paths[] = $squares;
            }
            
            if (count($lshapes) > 0) {
                array_unshift($lshapes, QR_AREA_LSHAPE);
                $this->paths[] = $lshapes;
            }
        }
        
        //----------------------------------------------------------------------
        private function reserveEdgeOnElem($elem, $edgeNo)
        {
            $this->tab_edges[$elem[QR_AREA_Y]][$elem[QR_AREA_X]][$edgeNo] = true;
        }
        
        //----------------------------------------------------------------------
        private function reserveEdge($px, $py, $edgeNo)
        {
            $this->tab_edges[$py][$px][$edgeNo] = true;
        }
        
         //----------------------------------------------------------------------
        private function markAdjacentEdges($group)
        {
            foreach($group->points as $elem) {
                if ($this->getOnElem($elem, -1, 0) == $group->id)
                    $this->reserveEdgeOnElem($elem, QR_AREA_W);
                    
                if ($this->getOnElem($elem, +1, 0) == $group->id)
                    $this->reserveEdgeOnElem($elem, QR_AREA_E);
                    
                if ($this->getOnElem($elem, 0, -1) == $group->id)
                    $this->reserveEdgeOnElem($elem, QR_AREA_N);
                
                if ($this->getOnElem($elem, 0, +1) == $group->id)
                    $this->reserveEdgeOnElem($elem, QR_AREA_S);
            }
        }
        
        //----------------------------------------------------------------------
        private function detectPaths(&$group)
        {
            $this->markAdjacentEdges($group);
            
            $elem = $group->points[0];
            $waylist = $this->findPath($group, $elem[QR_AREA_X], $elem[QR_AREA_Y]);
            $group->paths[] = array($elem[QR_AREA_X], $elem[QR_AREA_Y], $waylist);
            
            $tab = array();
            foreach($group->points as $elem) {
                
                $edgeTab = $this->tab_edges[$elem[QR_AREA_Y]][$elem[QR_AREA_X]];
                
                if (!(  $edgeTab[QR_AREA_N] 
                    &&  $edgeTab[QR_AREA_E] 
                    &&  $edgeTab[QR_AREA_S] 
                    &&  $edgeTab[QR_AREA_W])) {
                    
                    if (!$edgeTab[QR_AREA_S]) {
                       
                        $waylistw = $this->findPath($group, $elem[QR_AREA_X], $elem[QR_AREA_Y]+1);
                        $group->paths[] = array($elem[QR_AREA_X], $elem[QR_AREA_Y]+1, $waylistw);
                    }
                }
            }
  
        }
        
        //----------------------------------------------------------------------
        private function findPath($group, $sx, $sy)
        {
            $px = $sx;
            $py = $sy;
        
            $waylist = '';
            $dir = '';
            $lastdir = '';
            
            $moves = array(
            // magic :)
                0=>'',  1=>'L', 2=>'T', 3=>'L', 4=>'B', 5=>'B', 6=>'B,T', 7=>'B'
                ,8=>'R', 9=>'R,L', 10=>'T', 11=>'L',12=>'R',13=>'R',14=>'T',15=>''
            );
            
            do
            {
                $Q  = ($this->getAt($px-1, $py-1) == $group->id)?1:0;
                $Q += ($this->getAt($px, $py-1)   == $group->id)?2:0;
                $Q += ($this->getAt($px-1, $py)   == $group->id)?4:0;
                $Q += ($this->getAt($px, $py)     == $group->id)?8:0;
                
                if ($moves[$Q] == '') 
                    throw new Exception('It should NEVER happened!');
                    
                $move_expl = explode(',', $moves[$Q]);
                $have_way = false;
                
                $dir = '';
                
                while ((count($move_expl) > 0)&&($have_way == false)) {
                    $way = array_shift($move_expl);
                    
                    if (($have_way==false)&&($way=='R')&&($this->tab_edges[$py][$px][QR_AREA_N]==false)) {
                        $have_way = true;
                        $dir = $way;
                        $this->reserveEdge($px, $py, QR_AREA_N);
                        $px++;                      
                    } 
                    
                    if (($have_way==false)&&($way=='B')&&($this->tab_edges[$py][$px-1][QR_AREA_E]==false)) {
                        $have_way = true;
                        $dir = $way;
                        $this->reserveEdge($px-1, $py, QR_AREA_E);
                        $py++;                      
                    } 
                    
                    if (($have_way==false)&&($way=='L')&&($this->tab_edges[$py-1][$px-1][QR_AREA_S]==false)) {
                        $have_way = true;
                        $dir = $way;
                        $this->reserveEdge($px-1, $py-1, QR_AREA_S);
                        $px--;                      
                    } 
                    
                    if (($have_way==false)&&($way=='T')&&($this->tab_edges[$py-1][$px][QR_AREA_W]==false)) {
                        $have_way = true;
                        $dir = $way;
                        $this->reserveEdge($px, $py-1, QR_AREA_W);
                        $py--;                      
                    } 
                }

                $waylist .= $dir;
            
            } while (!(($px==$sx)&&($py==$sy)));
            
            return $waylist;
        }
    }
    
    /** @} */
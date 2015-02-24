<?php
/*
 * PHP QR Code encoder
 *
 * SVG output support
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
	
	class QRsvgOutput extends QRarea {
 
		public function __construct($source_tab) 
		{
			parent::__construct($source_tab);
		}

		//----------------------------------------------------------------------
        public function mapX($px)
		{	
			return $px;
		}

		//----------------------------------------------------------------------
        public function mapY($py)
		{
			return $py;
		}

		//----------------------------------------------------------------------
        public function getRawSvg()
		{
			$lib = array();
			$svg = array();
			
			$aggregate_paths = array();
			
			foreach ($this->paths as $path) {
				switch ($path[0]) {
					case QR_AREA_PATH:
							$pNum = 0;
							
							foreach($path[1] as $pathDetails) {
								
								$px = array_shift($pathDetails);
								$py = array_shift($pathDetails);
								$rle_steps = array_shift($pathDetails);
								
								$aggregate_add = 'M'.$px.','.$py.' ';
								
								while(count($rle_steps) > 0) {
								
									$delta = 1;
									
									$operator = array_shift($rle_steps);
									if (($operator != 'R') && ($operator != 'L') && ($operator != 'T') && ($operator != 'B')) {
										$delta = (int)$operator;
										$operator = array_shift($rle_steps);
									}
									
									if ($operator == 'R') $aggregate_add .= 'h'.$delta;
									if ($operator == 'L') $aggregate_add .= 'h-'.$delta;
									if ($operator == 'T') $aggregate_add .= 'v-'.$delta;
									if ($operator == 'B') $aggregate_add .= 'v'.$delta;
								}
								
								$aggregate_paths[] = $aggregate_add;
								
								$pNum++;
							}
	
						break;
					case QR_AREA_POINT:
								
							$symb = array_shift($path);
							
							while(count($path) > 0) {
								$px = array_shift($path);
								$py = array_shift($path);
								
								$aggregate_paths[] = 'M'.$px.','.$py.' v1h1v-1h-1';
							}
							
						break;
						
					case QR_AREA_RECT:
							
							$symb = array_shift($path);
							
							while(count($path) > 0) {
								$px = array_shift($path);
								$py = array_shift($path);
								$ex = array_shift($path);
								$ey = array_shift($path);
								
								$w = $ex-$px;
								$h = $ey-$py;
								
								$aggregate_paths[] = 'M'.$px.','.$py.' h'.$w.'v'.$h.'h-'.$w.'v-'.$h;
							}
							
						break;						
						
					case QR_AREA_LSHAPE:
								
							$symb = array_shift($path);
							
							$l_shapes[0] = 'm1,0h1v2h-2v-1h1z';
							$l_shapes[1] = 'h1v1h1v1h-2z';
							$l_shapes[2] = 'h2v2h-1v-1h-1z';
							$l_shapes[3] = 'h2v1h-1v1h-1z';
							
							while(count($path) > 0) {
								$px = array_shift($path);
								$py = array_shift($path);
								$mode = (int)array_shift($path);
								
								$aggregate_paths[] =  'M'.$px.','.$py.' '.$l_shapes[$mode];
							}
								
						break;	
						
					case QR_AREA_TRACKER:
								
							if (!isset($lib['tracker'])) {
								$lib['tracker'] = '<symbol id="tracker"><path d="m 0 7 0 7 7 0 0 -7 -7 0 z m 1 1 5 0 0 5 -5 0 0 -5 z m 1 1 0 3 3 0 0 -3 -3 0 z" style="fill:#000000;stroke:none"></path></symbol>';
							}
							
							$symb = array_shift($path);
							
							$px = array_shift($path);
							$py = array_shift($path);
								
							$svg[] = '<use x="'.$px.'" y="'.($py-7).'" xlink:href="#tracker"></use>';
									
						break;	
				}
			}
			
			$svg[] = '<path d="'.join(' ', $aggregate_paths).'" style="fill:#000000;stroke:none" ></path>';
							

			
			return join("\n", $lib)."\n".join("\n", $svg);
		}
	}
 
	/** @} */
	
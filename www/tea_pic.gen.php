<?php
include_once('config.inc.php');
include_once('functions.inc.php');

if(!isset($_GET['flavor_id']) || !isset($_GET['filling_id']))
{
	exit;
}

// Get tea information from DB
$conn = DB_GetConnection();
$flavor_color_hex = Helper_GetFlavorTeaColorCode($_GET['flavor_id'], $conn);
$flavor_color_arr = Helper_Hex2RGB($flavor_color_hex);

$multiplier = 1;
if(isset($_GET['thumbnail']) && $_GET['thumbnail'] == 1)
{
	$multiplier = 0.5;
}
else if(isset($_GET['scale']))
{
	$multiplier = $_GET['scale'];
}

// Colors
$bg_color_red = 255;
$bg_color_green = 255;
$bg_color_blue = 255;
$cup_color_red = 200;
$cup_color_green = 200;
$cup_color_blue = 200;
$cup_lid_color_red = 100;
$cup_lid_color_green = 100;
$cup_lid_color_blue = 100;
$tea_color_red = $flavor_color_arr['r'];
$tea_color_green = $flavor_color_arr['g'];
$tea_color_blue = $flavor_color_arr['b'];
$straw_color_red = 111;
$straw_color_green = 49;
$straw_color_blue = 152;
$straw_light_color_red = 181;
$straw_light_color_green = 163;
$straw_light_color_blue = 213;
$pearl_color_red = 70;
$pearl_color_green = 70;
$pearl_color_blue = 70;
$jelly_color_red = 255;
$jelly_color_green = 249;
$jelly_color_blue = 189;

// Coordinates
// Cup
$cup_tlx = 19 * $multiplier;
$cup_tly = 58 * $multiplier;
$cup_trx = 176 * $multiplier;
$cup_try = 58 * $multiplier;
$cup_blx = 47 * $multiplier;
$cup_bly = 294 * $multiplier;
$cup_brx = 153 * $multiplier;
$cup_bry = 294 * $multiplier;
$cup_lid_lx = 13 * $multiplier;
$cup_lid_ly = 58 * $multiplier;
$cup_lid_rx = 182 * $multiplier;
$cup_lid_ry = 58 * $multiplier;

// Tea
$tea_tlx = 21 * $multiplier;
$tea_tly = 76 * $multiplier;
$tea_trx = 174 * $multiplier;
$tea_try = 76 * $multiplier;

// Straw
$straw_tlx = 107 * $multiplier;
$straw_tly = 2 * $multiplier;
$straw_trx = 131 * $multiplier;
$straw_try = 5 * $multiplier;
$straw_blx = 98 * $multiplier;
$straw_bly = 57 * $multiplier;
$straw_brx = 122 * $multiplier;
$straw_bry = 57 * $multiplier;
$straw_light_tlx = 98 * $multiplier;
$straw_light_tly = 59 * $multiplier;
$straw_light_trx = 122 * $multiplier;
$straw_light_try = 59 * $multiplier;
$straw_light_blx = 95 * $multiplier;
$straw_light_bly = 75 * $multiplier;
$straw_light_brx = 119 * $multiplier;
$straw_light_bry = 75 * $multiplier;

// Pearls
$pearl_width = 21 * $multiplier;
$pearl_height = 20 * $multiplier;
$pearl[0]["x"] = 52 * $multiplier;
$pearl[0]["y"] = 241 * $multiplier;
$pearl[1]["x"] = 70 * $multiplier;
$pearl[1]["y"] = 250 * $multiplier;
$pearl[2]["x"] = 90 * $multiplier;
$pearl[2]["y"] = 243 * $multiplier;
$pearl[3]["x"] = 111 * $multiplier;
$pearl[3]["y"] = 245 * $multiplier;
$pearl[4]["x"] = 139 * $multiplier;
$pearl[4]["y"] = 249 * $multiplier;
$pearl[5]["x"] = 54 * $multiplier;
$pearl[5]["y"] = 263 * $multiplier;
$pearl[6]["x"] = 73 * $multiplier;
$pearl[6]["y"] = 270 * $multiplier;
$pearl[7]["x"] = 93 * $multiplier;
$pearl[7]["y"] = 263 * $multiplier;
$pearl[8]["x"] = 123 * $multiplier;
$pearl[8]["y"] = 261 * $multiplier;
$pearl[9]["x"] = 144 * $multiplier;
$pearl[9]["y"] = 269 * $multiplier;
$pearl[10]["x"] = 57 * $multiplier;
$pearl[10]["y"] = 283 * $multiplier;
$pearl[11]["x"] = 89 * $multiplier;
$pearl[11]["y"] = 283 * $multiplier;
$pearl[12]["x"] = 109 * $multiplier;
$pearl[12]["y"] = 276 * $multiplier;
$pearl[13]["x"] = 129 * $multiplier;
$pearl[13]["y"] = 283 * $multiplier;

// Jellies
$jelly[0] = array(50 * $multiplier,293 * $multiplier,
	57 * $multiplier,293 * $multiplier,
	57 * $multiplier,286 * $multiplier,
	50 * $multiplier,286 * $multiplier);
$jelly[1] = array(63 * $multiplier,293 * $multiplier,
70 * $multiplier,293 * $multiplier,
70 * $multiplier,286 * $multiplier,
63 * $multiplier,286 * $multiplier);
$jelly[2] = array(80 * $multiplier,293 * $multiplier,
87 * $multiplier,293 * $multiplier,
87 * $multiplier,286 * $multiplier,
80 * $multiplier,286 * $multiplier);
$jelly[3] = array(97 * $multiplier,293 * $multiplier,
104 * $multiplier,293 * $multiplier,
104 * $multiplier,286 * $multiplier,
97 * $multiplier,286 * $multiplier);
$jelly[4] = array(112 * $multiplier,293 * $multiplier,
119 * $multiplier,293 * $multiplier,
119 * $multiplier,286 * $multiplier,
112 * $multiplier,286 * $multiplier);
$jelly[5] = array(139 * $multiplier,293 * $multiplier,
146 * $multiplier,293 * $multiplier,
146 * $multiplier,286 * $multiplier,
139 * $multiplier,286 * $multiplier);
$jelly[6] = array(47 * $multiplier,285 * $multiplier,
54 * $multiplier,285 * $multiplier,
54 * $multiplier,278 * $multiplier,
47 * $multiplier,278 * $multiplier);
$jelly[7] = array(58 * $multiplier,286 * $multiplier,
65 * $multiplier,284 * $multiplier,
63 * $multiplier,277 * $multiplier,
56 * $multiplier,279 * $multiplier);
$jelly[8] = array(72 * $multiplier,287 * $multiplier,
79 * $multiplier,285 * $multiplier,
77 * $multiplier,278 * $multiplier,
70 * $multiplier,280 * $multiplier);
$jelly[9] = array(88 * $multiplier,285 * $multiplier,
95 * $multiplier,287 * $multiplier,
97 * $multiplier,280 * $multiplier,
90 * $multiplier,278 * $multiplier);
$jelly[10] = array(103 * $multiplier,283 * $multiplier,
108 * $multiplier,288 * $multiplier,
113 * $multiplier,283 * $multiplier,
108 * $multiplier,278 * $multiplier);
$jelly[11] = array(116 * $multiplier,285 * $multiplier,
123 * $multiplier,285 * $multiplier,
123 * $multiplier,278 * $multiplier,
116 * $multiplier,278 * $multiplier);
$jelly[12] = array(127 * $multiplier,285 * $multiplier,
134 * $multiplier,285 * $multiplier,
134 * $multiplier,278 * $multiplier,
127 * $multiplier,278 * $multiplier);
$jelly[13] = array(143 * $multiplier,284 * $multiplier,
150 * $multiplier,286 * $multiplier,
152 * $multiplier,279 * $multiplier,
145 * $multiplier,277 * $multiplier);
$jelly[14] = array(77 * $multiplier,276 * $multiplier,
81 * $multiplier,283 * $multiplier,
88 * $multiplier,279 * $multiplier,
84 * $multiplier,272 * $multiplier);
$jelly[15] = array(97 * $multiplier,274 * $multiplier,
99 * $multiplier,281 * $multiplier,
106 * $multiplier,279 * $multiplier,
104 * $multiplier,272 * $multiplier);
$jelly[16] = array(46 * $multiplier,273 * $multiplier,
53 * $multiplier,277 * $multiplier,
57 * $multiplier,270 * $multiplier,
50 * $multiplier,266 * $multiplier);
$jelly[17] = array(64 * $multiplier,276 * $multiplier,
71 * $multiplier,278 * $multiplier,
73 * $multiplier,271 * $multiplier,
66 * $multiplier,269 * $multiplier);
$jelly[18] = array(85 * $multiplier,270 * $multiplier,
89 * $multiplier,277 * $multiplier,
96 * $multiplier,273 * $multiplier,
92 * $multiplier,266 * $multiplier);
$jelly[19] = array(107 * $multiplier,276 * $multiplier,
114 * $multiplier,280 * $multiplier,
118 * $multiplier,273 * $multiplier,
111 * $multiplier,269 * $multiplier);
$jelly[20] = array(120 * $multiplier,273 * $multiplier,
125 * $multiplier,278 * $multiplier,
130 * $multiplier,273 * $multiplier,
125 * $multiplier,268 * $multiplier);
$jelly[21] = array(136 * $multiplier,280 * $multiplier,
143 * $multiplier,278 * $multiplier,
141 * $multiplier,271 * $multiplier,
134 * $multiplier,273 * $multiplier);
$jelly[21] = array(143 * $multiplier,272 * $multiplier,
148 * $multiplier,277 * $multiplier,
153 * $multiplier,272 * $multiplier,
148 * $multiplier,267 * $multiplier);
$jelly[22] = array(73 * $multiplier,269 * $multiplier,
80 * $multiplier,273 * $multiplier,
84 * $multiplier,266 * $multiplier,
77 * $multiplier,262 * $multiplier);
$jelly[23] = array(95 * $multiplier,265 * $multiplier,
97 * $multiplier,271 * $multiplier,
104 * $multiplier,270 * $multiplier,
102 * $multiplier,263 * $multiplier);
$jelly[24] = array(114 * $multiplier,264 * $multiplier,
118 * $multiplier,271 * $multiplier,
124 * $multiplier,267 * $multiplier,
121 * $multiplier,260 * $multiplier);
$jelly[25] = array(127 * $multiplier,268 * $multiplier,
134 * $multiplier,272 * $multiplier,
138 * $multiplier,265 * $multiplier,
131 * $multiplier,261 * $multiplier);
$jelly[26] = array(140 * $multiplier,268 * $multiplier,
147 * $multiplier,266 * $multiplier,
145 * $multiplier,259 * $multiplier,
138 * $multiplier,261 * $multiplier);
$jelly[27] = array(145 * $multiplier,259 * $multiplier,
153 * $multiplier,262 * $multiplier,
155 * $multiplier,255 * $multiplier,
148 * $multiplier,253 * $multiplier);
$jelly[28] = array(103 * $multiplier,263 * $multiplier,
107 * $multiplier,270 * $multiplier,
114 * $multiplier,266 * $multiplier,
110 * $multiplier,259 * $multiplier);
$jelly[29] = array(60 * $multiplier,260 * $multiplier,
56 * $multiplier,267 * $multiplier,
63 * $multiplier,271 * $multiplier,
67 * $multiplier,264 * $multiplier);
$jelly[30] = array(83 * $multiplier,260 * $multiplier,
85 * $multiplier,267 * $multiplier,
92 * $multiplier,265 * $multiplier,
90 * $multiplier,258 * $multiplier);
$jelly[31] = array(124 * $multiplier,262 * $multiplier,
131 * $multiplier,260 * $multiplier,
129 * $multiplier,253 * $multiplier,
122 * $multiplier,255 * $multiplier);
$jelly[32] = array(131 * $multiplier,254 * $multiplier,
135 * $multiplier,261 * $multiplier,
142 * $multiplier,257 * $multiplier,
138 * $multiplier,250 * $multiplier);
$jelly[33] = array(102 * $multiplier,254 * $multiplier,
109 * $multiplier,254 * $multiplier,
109 * $multiplier,247 * $multiplier,
102 * $multiplier,247 * $multiplier);
$jelly[34] = array(109 * $multiplier,257 * $multiplier,
116 * $multiplier,261 * $multiplier,
120 * $multiplier,254 * $multiplier,
113 * $multiplier,250 * $multiplier);
$jelly[35] = array(92 * $multiplier,259 * $multiplier,
99 * $multiplier,263 * $multiplier,
103 * $multiplier,256 * $multiplier,
96 * $multiplier,252 * $multiplier);
$jelly[36] = array(76 * $multiplier,255 * $multiplier,
81 * $multiplier,260 * $multiplier,
86 * $multiplier,255 * $multiplier,
81 * $multiplier,250 * $multiplier);
$jelly[37] = array(68 * $multiplier,263 * $multiplier,
75 * $multiplier,263 * $multiplier,
75 * $multiplier,256 * $multiplier,
68 * $multiplier,256 * $multiplier);
$jelly[38] = array(61 * $multiplier,252 * $multiplier,
66 * $multiplier,257 * $multiplier,
71 * $multiplier,252 * $multiplier,
66 * $multiplier,247 * $multiplier);
$jelly[39] = array(55 * $multiplier,261 * $multiplier,
62 * $multiplier,257 * $multiplier,
58 * $multiplier,250 * $multiplier,
51 * $multiplier,254 * $multiplier);
$jelly[40] = array(46 * $multiplier,266 * $multiplier,
53 * $multiplier,264 * $multiplier,
51 * $multiplier,257 * $multiplier,
44 * $multiplier,259 * $multiplier);
$jelly[41] = array(126 * $multiplier,293 * $multiplier,
133 * $multiplier,293 * $multiplier,
133 * $multiplier,286 * $multiplier,
126 * $multiplier,286 * $multiplier);
$jelly[42] = array(136 * $multiplier,280 * $multiplier,
143 * $multiplier,278 * $multiplier,
141 * $multiplier,271 * $multiplier,
134 * $multiplier,273 * $multiplier);

// Brush settings
$cup_brush_thickness = 1;
$cup_lid_brush_thickness =2;

// Initialize the image
$im = imagecreate(200 * $multiplier, 300 * $multiplier);
$bg = imagecolorallocate($im, $bg_color_red, $bg_color_green, $bg_color_blue);

// Draw the straw
$straw_color = imagecolorallocate($im, $straw_color_red, $straw_color_green, $straw_color_blue);
$straw_point_arr = array(
	$straw_tlx, $straw_tly,
	$straw_trx, $straw_try,
	$straw_brx, $straw_bry,
	$straw_blx, $straw_bly
	);
imagefilledpolygon($im, $straw_point_arr, 4, $straw_color);
$straw_light_color = imagecolorallocate($im, $straw_light_color_red, $straw_light_color_green, $straw_light_color_blue);
$straw_light_point_arr = array(
	$straw_light_tlx, $straw_light_tly,
	$straw_light_trx, $straw_light_try,
	$straw_light_brx, $straw_light_bry,
	$straw_light_blx, $straw_light_bly
	);
imagefilledpolygon($im, $straw_light_point_arr, 4, $straw_light_color);

// Draw the tea
$tea_color = imagecolorallocate($im, $tea_color_red, $tea_color_green, $tea_color_blue);
$tea_point_arr = array(
	$tea_tlx, $tea_tly,
	$tea_trx, $tea_try,
	$cup_brx, $cup_bry,
	$cup_blx, $cup_bly
	);
imagefilledpolygon($im, $tea_point_arr, 4, $tea_color);

if($_GET['filling_id'] == 1)
{
	// Draw the pearls
	imagesetthickness($im, $cup_brush_thickness);
	$pearl_color = imagecolorallocate($im, $pearl_color_red, $pearl_color_green, $pearl_color_blue);
	imagefilledellipse($im, $pearl[0]["x"], $pearl[0]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[1]["x"], $pearl[1]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[2]["x"], $pearl[2]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[3]["x"], $pearl[3]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[4]["x"], $pearl[4]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[5]["x"], $pearl[5]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[6]["x"], $pearl[6]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[7]["x"], $pearl[7]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[8]["x"], $pearl[8]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[9]["x"], $pearl[9]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[10]["x"], $pearl[10]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[11]["x"], $pearl[11]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[12]["x"], $pearl[12]["y"], $pearl_width, $pearl_height, $pearl_color);
	imagefilledellipse($im, $pearl[13]["x"], $pearl[13]["y"], $pearl_width, $pearl_height, $pearl_color);
}
else if($_GET['filling_id'] == 2)
{
	// Draw the jellies
	imagesetthickness($im, $cup_brush_thickness);
	$jelly_color = imagecolorallocate($im, $jelly_color_red, $jelly_color_green, $jelly_color_blue);
	imagefilledpolygon($im, $jelly[0], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[1], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[2], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[3], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[4], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[5], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[6], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[7], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[8], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[9], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[10], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[11], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[12], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[13], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[14], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[15], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[16], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[17], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[18], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[19], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[20], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[21], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[22], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[23], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[24], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[25], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[26], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[27], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[28], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[29], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[30], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[31], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[32], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[33], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[34], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[35], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[36], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[37], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[38], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[39], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[40], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[41], 4, $jelly_color);
	imagefilledpolygon($im, $jelly[42], 4, $jelly_color);
}

// Draw the cup
$cup_color = imagecolorallocate($im, $cup_color_red, $cup_color_green, $cup_color_blue);
imageline($im, $cup_tlx, $cup_tly, $cup_blx, $cup_bly, $cup_color);
imageline($im, $cup_trx, $cup_try, $cup_brx, $cup_bry, $cup_color);
imageline($im, $cup_blx, $cup_bly, $cup_brx, $cup_bry, $cup_color);
imagesetthickness($im, $cup_lid_brush_thickness);
$cup_lid_color = imagecolorallocate($im, $cup_lid_color_red, $cup_lid_color_green, $cup_lid_color_blue);
imageline($im, $cup_lid_lx, $cup_lid_ly, $cup_lid_rx, $cup_lid_ry, $cup_lid_color);

// Display the image
header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
?>
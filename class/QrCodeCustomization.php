<?php 
namespace Aaz;

/**
* 	Customisation d'un QRCODE standard venant du domaine https://chart.googleapis.com/chart
*	L'on peut customiser:
*	1- les eyes
*	2- les iris
*	3- la couleur
*	4- le logo
*/
class QrCodeCustomization{
	const EYE_ARRONDI = "arrondi";
	const EYE_ARRONDI_ROND = "arrondi_rond";
	const EYE_BOUCLIER = "bouclier";
	const EYE_COUSSIN = "coussin";
	const EYE_DIAMANT = "diamant";
	const EYE_ETOILE = "etoile";
	const EYE_GALBE = "galbe";
	const EYE_GRILLE = "grille";
	const EYE_MORPION = "morpion";
	const EYE_OCTOGONE = "octogone";
	const EYE_OEIL_DROIT = "oeil_droit";
	const EYE_OEIL_GAUCHE = "oeil_gauche";
	const EYE_POINTU = "pointu";
	const EYE_RONDS = "ronds";
	const EYE_SIMPLE_ROND = "simple_rond";
	const EYE_TRAMIS = "tramis";
	const EYE_VAGUE = "vague";
	const EYE_CARRE = "carre";
	const EYE_ALIEN = "alien";
	const EYE_FEUILLE = "feuille";
	const EYE_ROND = "rond";
	const EYE_ROND_ETOILE = "rond_etoile";
	const EYE_ROND_ETOILE_2 = "rond_etoile_2";
	const EYE_ROND_ETOILE_GAUFRE = "rond_etoile_gaufre";


	public $eye_path;
	public $brush_path;
	/**
	 * [$source l'image source]
	 * @var [ressource]
	 */
	private $source;
	private $sourceW;
	private $sourceH;
	/**
	 * [$original_source une copie original de l'image source]
	 * @var [ressource]
	 */
	private $original_source;

	/**
	 * [$moduleWidth la largeur des petits modules]
	 * @var [int]
	 */
	private $moduleWidth;
	/**
	 * [$eye type d'oeil à utiliser]
	 * @var [enum]
	 */
	private $eye;
	/**
	 * [$logo chemin absolus du l'image à placer comme logo]
	 * @var [type]
	 */
	private $logo;
	/**
	 * [$options sert au parametrage de l'image à produire]
	 * @var [type]
	 */
	private $options;
	/**
	 * [$eyeTl l'oeil à gauche]
	 * @var [type]
	 */
	private $eyeTl;
	/**
	 * [$eyeTr l'oeil à droit]
	 * @var [type]
	 */
	private $eyeTr;
	/**
	 * [$eyeBl l'oeil en bas]
	 * @var [type]
	 */
	private $eyeBl;

	public function __construct ($source=null,$brush="round"){
		$this->source = $source;
		$this->sourceW = imagesx($this->source);
		$this->sourceH = imagesy($this->source);

		// on realise une copie avant toute modification
		$this->original_source = imagecreatetruecolor($this->sourceW,$this->sourceH);
		imagecopy($this->original_source,$this->source,0,0,0,0,$this->sourceW ,$this->sourceH);


		$this->setEye(QrCodeCustomization::EYE_CARRE);

		$this->brush_path = __DIR__."/../images/brush/$brush.png";
		$this->logo = __DIR__."/../images/logo.png";
		$this->options = array(
			"bg"=>"000000",
			"eyes"=>array(
				"global"=>"000000",
				"topLeft"=>"000000",
				"bottomLeft"=>"000000",
				"topRight"=>"000000",
			),
			"iris"=>array(
				"global"=>"000000",
				"topLeft"=>"000000",
				"bottomLeft"=>"000000",
				"topRight"=>"000000",
			),
			"logo"=>array(
				"creux"=>true
			)
		);
	}

	public function setOptions($key,$value){
		if(isset($this->options[$key])){
			if(is_array($this->options[$key])){
				$this->options[$key] = array_merge($this->options[$key],$value);
			}
			else{
				$this->options[$key] = $value;
			}

			if($key == "eyes"){
				$this->setEye($this->options[$key]["model"]);
			}
		}
	}
	public function getOptions($key=null){
		return (isset($this->options[$key]))?$this->options[$key]:($key==null?$this->options:null);
	}

	public function setLogo($path){
		$this->logo = $path;
	}
	public function getLogo(){
		return $this->logo;
	}

	public function setEye($name){

		switch ($name) {
			case QrCodeCustomization::EYE_ARRONDI:
			case QrCodeCustomization::EYE_ARRONDI_ROND:
			case QrCodeCustomization::EYE_BOUCLIER:
			case QrCodeCustomization::EYE_COUSSIN:
			case QrCodeCustomization::EYE_DIAMANT:
			case QrCodeCustomization::EYE_ETOILE:
			case QrCodeCustomization::EYE_GALBE:
			case QrCodeCustomization::EYE_GRILLE:
			case QrCodeCustomization::EYE_MORPION:
			case QrCodeCustomization::EYE_OCTOGONE:
			case QrCodeCustomization::EYE_OEIL_DROIT:
			case QrCodeCustomization::EYE_OEIL_GAUCHE:
			case QrCodeCustomization::EYE_POINTU:
			case QrCodeCustomization::EYE_RONDS:
			case QrCodeCustomization::EYE_SIMPLE_ROND:
			case QrCodeCustomization::EYE_TRAMIS:
			case QrCodeCustomization::EYE_VAGUE:
			case QrCodeCustomization::EYE_ALIEN:
			case QrCodeCustomization::EYE_FEUILLE:
			case QrCodeCustomization::EYE_ROND:
			case QrCodeCustomization::EYE_ROND_ETOILE:
			case QrCodeCustomization::EYE_ROND_ETOILE_2:
			case QrCodeCustomization::EYE_ROND_ETOILE_GAUFRE:

			break;

			default:
				$name = QrCodeCustomization::EYE_CARRE;
			break;
		}
		$this->eye_path = __DIR__."/../images/eyes/$name.png";
		$this->eye = $name;
		$this->options["eyes"]["model"] = $name;
	}
	public function getEye(){
		return $this->eye;
	}

	public function setBrush($name){
		$this->brush_path = __DIR__."/../images/brush/$name.png";
		$this->brush = $name;
	}
	public function getBrush(){
		return $this->brush;
	}

	private function isSquare($p1,$p2){

		$height = $p2["y"] - $p1["y"];
		$hrow = array();
		$p1_2 = null;
		$p2_2 = null;
		$p3 = null;
		$limit =  $p1["x"]+$height+1;

		$_p1 = null;
		$_p2 = null;


		// deplacement topLeft -----> topRight
		for ($i=$p1["x"]; $i <= $limit ; $i++) { 

			if($i >= $this->sourceW )
				continue;

			$rgb = imagecolorat ($this->source ,$i ,$p1["y"] );
			$colors = imagecolorsforindex($this->source, $rgb);

			
			if($colors['red'] == 0 && $colors['green'] == 0 && $colors['blue'] == 0){
			
				$point = array("x"=>$i,"y"=>$p1["y"],"colors"=>$colors);
				$hrow[] = $point;
			}
			else{
				if(!empty($hrow)) {
					$prev = $hrow[count($hrow)-1];
					if(!empty($prev)) {

						if($limit == $i){
							$hrow[] = array();
							$p1_2 = $prev;
						}							
					}else{
						$p1_2 = null;
					}
				}
				break;
			}
		}


		if($p1_2){
			// deplacement bottomLeft ----------> bottomRight
			for ($i=$p2["x"]; $i <= $limit ; $i++){ 
				if($i >= $this->sourceW )
					continue;

				$rgb = imagecolorat ($this->source ,$i ,$p2["y"] );
				$colors = imagecolorsforindex($this->source, $rgb);

				
				if($colors['red'] == 0 && $colors['green'] == 0 && $colors['blue'] == 0){
					$point = array("x"=>$i,"y"=>$p2["y"],"colors"=>$colors);
					$hrow[] = $point;
				}
				else{
					if(!empty($hrow)) {
						$prev = $hrow[count($hrow)-1];
						if(!empty($prev)) {
							if($limit == $i){
								$hrow[] = array();
								$p2_2 = $prev;
							}
						}
						else{
							$p2_2 = null;
						}
					}
				}
			}
		}
		
		return ($p1_2 && $p2_2) ? array("height"=>$height,"coords"=>array($p1_2,$p2_2)):null;
	}

	private function calcModuleWidth($p1,$p2){
		$height = $p2["y"] - $p1["y"];
		$middle_y = $p1["y"]+round($height/2);
		$hrow = array();
		$width = 0;

		for ($i=$p1["x"]; $i <= $this->sourceW ; $i++) { 

			$rgb = imagecolorat ($this->original_source ,$i ,$middle_y);
			$colors = imagecolorsforindex($this->original_source, $rgb);
			if($colors['red'] == 255 && $colors['green'] == 255 && $colors['blue'] == 255){
				$x_ = $i-1;
				$width = $x_ - $p1["x"];
				break;	
			}
		}
		return $width;
	}

	private function detectEyes(){
		$eyes = array();
		$firstEye = null;


		// nouvelle formule
		for ($x=0; $x < $this->sourceW ; $x++) { 
			$p1 = null;
			$p2 = null;
			
			for ($y=0; $y < $this->sourceH ; $y++) { 

				$rgb = imagecolorat ($this->source ,$x ,$y );
				$colors = imagecolorsforindex($this->source, $rgb);

				if($colors['red'] == 0 && $colors['green'] == 0 && $colors['blue'] == 0){
					$point = array("x"=>$x,"y"=>$y);
					if(!$p1){
						$p1 = $point;
					}
				}
				else{

					if($p1){
						$p2 = array("x"=>$x,"y"=>$y-1);
						if(($isSquare = $this->isSquare($p1,$p2))){
							$eyes[] = array(
								"pos"=>count($eyes),
								"height"=>intval($isSquare["height"]),
								"data"=>array(array($p1["x"],$p1["y"]),array($isSquare["coords"][0]["x"],$isSquare["coords"][0]["y"]),array($isSquare["coords"][1]["x"],$isSquare["coords"][1]["y"]),array($p2["x"],$p2["y"]))
							);

							if(!$firstEye){
								$this->moduleWidth = $this->calcModuleWidth($p1,$p2);
								$firstEye = true;
								break;
							}
						}
					}
					$p1 = null;
					$p2 = null;
				}
			}

			if($firstEye)
				break;
		}

		if(!count($eyes)){
			throw new Exception("Qrcode eyes not detected yet", 1);
		}

		$eyes[0]['pos'] = 1;
		$eyes[0]['height'] +=2;
		$eyes[0]['data'][1][0] +=2;
		$eyes[0]['data'][3][0] +=2;
		$eyes[0]['data'][3][1] +=2;
		$eyes[0]['data'][2][1] +=2;
		$firstEye = $eyes[0];

		// eye bottomLeft
		$bl_x = $firstEye["data"][0][0];
		$bl_y = $this->sourceH-$firstEye["data"][0][1];//-1;
		$br_x = $bl_x+$firstEye["height"];
		$br_y = $bl_y;
		$tl_x = $bl_x;
		$tl_y = $bl_y-$firstEye["height"];
		$tr_x = $tl_x +$firstEye["height"];
		$tr_y = $tl_y;

		$eyes[] = array(
			"pos"=>2,
			"height"=>$bl_y-$tl_y,
			"data"=>array(array($tl_x,$tl_y),array($tr_x,$tr_y),array($br_x,$br_y),array($bl_x,$bl_y))
		);

		// eye topRight
		$tr_x = $this->sourceW - $firstEye["data"][0][0];//-1;
		$tr_y = $firstEye["data"][0][1];
		$tl_x = $tr_x-$firstEye["height"];
		$tl_y = $tr_y;
		$bl_x = $tl_x;
		$bl_y = $tl_y+$firstEye["height"];
		$br_x = $bl_x+$firstEye["height"];
		$br_y = $bl_y;

		$eyes[] = array(
			"pos"=>3,
			"height"=>$bl_y-$tl_y,
			"data"=>array(array($tl_x,$tl_y),array($tr_x,$tr_y),array($br_x,$br_y),array($bl_x,$bl_y))
		);

		$this->eyeTl = $eyes[0];
		$this->eyeBl = $eyes[1];
		$this->eyeTr = $eyes[2];

		// ancienne formule
		/*for ($x=0; $x < $this->sourceW ; $x++) { 

			$vrow = array();
			$p1 = null;
			$p2 = null;

			$p1_1 = null;
			$p2_1 = null;
			
			for ($y=0; $y < $this->sourceH ; $y++) { 

				$rgb = imagecolorat ($this->source ,$x ,$y );
				$colors = imagecolorsforindex($this->source, $rgb);

				if($colors['red'] == 0 && $colors['green'] == 0 && $colors['blue'] == 0){
					$point = array("x"=>$x,"y"=>$y);
					if(!$p1){
						$p1 = $point;
					}
				}
				else{

					if($p1){
						$p2 = array("x"=>$x,"y"=>$y-1);
						if(($isSquare = $this->searchEyes($p1,$p2))){
							$eyes[] = array(
								"pos"=>count($eyes),
								"height"=>intval($isSquare["height"]),
								"data"=>array(array($p1["x"],$p1["y"]),array($isSquare["coords"][0]["x"],$isSquare["coords"][0]["y"]),array($isSquare["coords"][1]["x"],$isSquare["coords"][1]["y"]),array($p2["x"],$p2["y"]))
							);

							if(!$firstEye){
								$this->moduleWidth = $this->calcModuleWidth($p1,$p2);
								$firstEye = true;
							}
						}
					}
					$p1 = null;
					$p2 = null;
				}
			}
		}*/
		return $eyes;
	}

	private function renderEyes(&$squares=array()){
		
		if(!count($squares)){
			return;
			//throw new Exception("Eyes not detected", 1);
		}

		$white = imagecolorallocate($this->source, 255, 255, 255);
		$red = imagecolorallocate($this->source, 255, 0, 0);

		//ancienne formule
		usort($squares,function($a,$b){
			if($a['height'] < $b['height'])
				return 1;
			else if($a['height'] > $b['height'])
				return -1;
			else 
				return 0;
		});
		$squares = array_values($squares);

		$eye_size = $squares[0]["height"];

		// ancienne formule
		// eyes selections
		$eye_squares = array_filter($squares,function($el)use($eye_size){
			return ($el["height"] == $eye_size);
		});
		$eye_squares = array_values($eye_squares);
		usort($eye_squares,function($a,$b){
			if($a['pos'] < $b['pos'])
				return -1;
			else if($a['pos'] > $b['pos'])
				return 1;
			else 
				return 0;
		});
		$eye_squares = array_values($eye_squares);

		// ancienne formule
		/*$rest = array_values($rest);
		$iris_size = $rest[0]["height"];

		// iris selections
		$iris_squares = array_filter($squares,function($el)use($iris_size){
			return ($el["height"] == $iris_size);
		});
		$iris_squares = array_values($iris_squares);
		usort($iris_squares,function($a,$b){
			if($a['pos'] < $b['pos'])
				return -1;
			else if($a['pos'] > $b['pos'])
				return 1;
			else 
				return 0;
		});
		$iris_squares = array_values($iris_squares);*/

		
		$eye_sprite = imagecreatefrompng($this->eye_path);
		$options_eyes = $this->getOptions('eyes');

		// on fait le template des yeux brute
		// pour une sauvegarde de l'image
		foreach ($eye_squares as $i=> $value) {

			$tl_x = $value['data'][0][0];
			$tl_y = $value['data'][0][1];
			$tr_x = $value['data'][1][0];
			$tr_y = $value['data'][1][1];

			$bl_x = $value['data'][3][0];
			$bl_y = $value['data'][3][1];
			$br_x = $value['data'][2][0];
			$br_y = $value['data'][2][1];

			if($value["height"] == $eye_size){

				$eye = imagescale($eye_sprite,$eye_size,$eye_size);
				$eye_w = imagesx($eye);
				$eye_h = imagesy($eye);

				if($i == 0){
					$this->eyeTl = $value;
				}
				else if($i == 1){
					$this->eyeBl = $value;
				}
				else if($i == 2){
					$this->eyeTr = $value;
				}

				switch ($this->eye) {
					case QrCodeCustomization::EYE_ALIEN:
					case QrCodeCustomization::EYE_FEUILLE:
					case QrCodeCustomization::EYE_GRILLE:
						if($i == 0){
							$this->eyeTl = $value;
						}
						else if($i == 1){
							imageflip($eye,IMG_FLIP_VERTICAL);
							$this->eyeBl = $value;
						}
						else if($i == 2){
							imageflip($eye,IMG_FLIP_HORIZONTAL);
							$this->eyeTr = $value;
						}
					break;
				}

				imagefilledrectangle ($this->source ,$tl_x ,$tl_y ,$br_x ,$br_y ,$white);
				imagecopymerge($this->source,$eye,$tl_x,$tl_y,0,0,$eye_w,$eye_h, 100);	
				imagedestroy($eye);
			}
		}

		// on realise une copie actuelle avant ajouts de couleur
		$source_copy = imagecreatetruecolor($this->sourceW,$this->sourceH);
		imagecopy ($source_copy ,$this->source,0,0,0,0,$this->sourceW ,$this->sourceH);


		// on defini la couleur global du qrcode
		if(($codec = $this->hex2RGB($this->options["bg"]))){
			imagefilter($this->source, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
		}

		// on modifie la couleur yeux
		foreach ($eye_squares as $i=> $value) {

			$tl_x = $value['data'][0][0];
			$tl_y = $value['data'][0][1];
			$tr_x = $value['data'][1][0];
			$tr_y = $value['data'][1][1];

			$bl_x = $value['data'][3][0];
			$bl_y = $value['data'][3][1];
			$br_x = $value['data'][2][0];
			$br_y = $value['data'][2][1];

			if($value["height"] == $eye_size){

				$eye = imagescale($eye_sprite,$eye_size,$eye_size);

				switch ($this->eye) {
					case QrCodeCustomization::EYE_ALIEN:
					case QrCodeCustomization::EYE_FEUILLE:
					case QrCodeCustomization::EYE_GRILLE:
					case QrCodeCustomization::EYE_ROND:

						if($i == 0){

						}
						else if($i == 1){
							imageflip($eye,IMG_FLIP_VERTICAL);
						}
						else if($i == 2){
							imageflip($eye,IMG_FLIP_HORIZONTAL);
						}
					break;

					
				}


				if($i == 0){ // topLeft
					if($options_eyes["topLeft"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["topLeft"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["global"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($this->options["bg"] != "000000") {
						if(($codec = $this->hex2RGB($this->options["bg"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
				}
				else if($i == 1){ // bottomLeft
					if($options_eyes["bottomLeft"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["bottomLeft"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["global"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($this->options["bg"] != "000000") {
						if(($codec = $this->hex2RGB($this->options["bg"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
				}
				else if($i == 2){ // topRight
					if($options_eyes["topRight"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["topRight"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["global"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($this->options["bg"] != "000000") {
						if(($codec = $this->hex2RGB($this->options["bg"]))){
							imagefilter($eye, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
				}

				$eye_w = imagesx($eye);
				$eye_h = imagesy($eye);

				imagefilledrectangle ($this->source ,$tl_x ,$tl_y ,$br_x ,$br_y ,$white);
				imagecopymerge($this->source,$eye,$tl_x,$tl_y,0,0,$eye_w,$eye_h, 100);	
				imagedestroy($eye);
			}
		}

		
		// on modifie la couleur le l'iris
		$options_iris = $this->getOptions('iris');
		
		foreach ($eye_squares as $i=> $value) {

			if($value["height"] == $eye_size){

				$reducer = 1;
				$reducer_cmp = 0;
				$iris_w = $iris_h = $eye_squares[$i]["height"] - ($this->moduleWidth*$reducer);

				$iris_center = round($eye_size/2);
				$iris_quart = round($iris_center/2);

				switch ($this->eye) {
					
					case QrCodeCustomization::EYE_ARRONDI:
					case QrCodeCustomization::EYE_ARRONDI_ROND:
					case QrCodeCustomization::EYE_COUSSIN:
					case QrCodeCustomization::EYE_DIAMANT:
					case QrCodeCustomization::EYE_OEIL_DROIT:
					case QrCodeCustomization::EYE_OEIL_GAUCHE:
					case QrCodeCustomization::EYE_VAGUE:
					case QrCodeCustomization::EYE_FEUILLE:
					case QrCodeCustomization::EYE_GRILLE:
					case QrCodeCustomization::EYE_OCTOGONE:
					case QrCodeCustomization::EYE_ETOILE:
					case QrCodeCustomization::EYE_GALBE:
						//$reducer_cmp = 8;
					break;

					case QrCodeCustomization::EYE_ALIEN:
						//$reducer_cmp = 7;
					break;

					case QrCodeCustomization::EYE_BOUCLIER:
						//$reducer_cmp = 6;
					break;
				}

				

				$tl_x = $eye_squares[$i]["data"][0][0] + ($this->moduleWidth*$reducer)+$reducer_cmp;
				$tl_y = $eye_squares[$i]["data"][0][1] + ($this->moduleWidth*$reducer)+$reducer_cmp;
				$tr_x = $eye_squares[$i]["data"][1][0] - ($this->moduleWidth*$reducer)-$reducer_cmp;
				$tr_y = $eye_squares[$i]["data"][1][1] + ($this->moduleWidth*$reducer)-$reducer_cmp;
				
				$bl_x = $eye_squares[$i]["data"][3][0] + ($this->moduleWidth*$reducer)+$reducer_cmp;
				$bl_y = $eye_squares[$i]["data"][3][1] - ($this->moduleWidth*$reducer)-$reducer_cmp;
				$br_x = $eye_squares[$i]["data"][2][0] - ($this->moduleWidth*$reducer)-$reducer_cmp;
				$br_y = $eye_squares[$i]["data"][2][1] - ($this->moduleWidth*$reducer)-$reducer_cmp;



				// nouvelle formule
				$tl_x = ($eye_squares[$i]["data"][0][0]+$iris_quart);
				$tl_y = ($eye_squares[$i]["data"][0][1]+$iris_quart);
				$bl_x = $tl_x;
				$bl_y = ($eye_squares[$i]["data"][3][1]-$iris_quart);
				$tr_x = ($eye_squares[$i]["data"][1][0]-$iris_quart);
				$tr_y = $tl_y;
				$br_x = $tr_x;
				$br_y = $bl_y;
				// fin de la nouvelle formule
				// 
				switch ($this->eye){
					case QrCodeCustomization::EYE_POINTU:
						$tl_y -= 2;
						$tr_y -= 2;
					break;

					case QrCodeCustomization::EYE_BOUCLIER:
						$tr_x += 2;
						$br_x += 2;

						$bl_y += 2;
						$br_y += 2;
					break;

					case QrCodeCustomization::EYE_ALIEN:
						$bl_y +=1;
						$br_y +=1;
						$tr_x +=1;
						$br_x +=1;
					break;
				}

				$iris_w = $tr_x - $tl_x;
				$iris_h = $bl_y - $tl_y;

				$iris = imagecreatetruecolor($iris_w,$iris_h);
				$blanc = imagecolorallocate ($iris, 255, 255,255);
				imagefill($iris,0, 0,$blanc);
				imagecolortransparent($iris,$blanc);


				imagefilledellipse($this->source,round(($tl_x+$tr_x)/2),round(($tl_y+$bl_y)/2),$iris_w,$iris_h,$white);

				imagecopymerge($iris,$source_copy,0,0,$tl_x,$tl_y,$iris_w,$iris_h,100);
				//imagecopymerge($iris,$source_copy,0,0,$tl_x,$tl_y,$iris_w,$iris_h,100);

				if($i == 0){ // topLeft
					if($options_iris["topLeft"] != "000000"){
						if(($codec = $this->hex2RGB($options_iris["topLeft"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_iris["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_iris["global"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["topLeft"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["topLeft"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["global"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($this->options["bg"] != "000000"){
						if(($codec = $this->hex2RGB($this->options["bg"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
				}
				else if($i == 1){ // bottomLeft
					if($options_iris["bottomLeft"] != "000000"){
						if(($codec = $this->hex2RGB($options_iris["bottomLeft"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_iris["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_iris["global"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["bottomLeft"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["bottomLeft"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["global"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($this->options["bg"] != "000000"){
						if(($codec = $this->hex2RGB($this->options["bg"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
				}
				else if($i == 2){ // topRight
					if($options_iris["topRight"] != "000000"){
						if(($codec = $this->hex2RGB($options_iris["topRight"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_iris["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_iris["global"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["topRight"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["topRight"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($options_eyes["global"] != "000000"){
						if(($codec = $this->hex2RGB($options_eyes["global"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
					else if($this->options["bg"] != "000000"){
						if(($codec = $this->hex2RGB($this->options["bg"]))){
							imagefilter($iris, IMG_FILTER_COLORIZE,$codec["red"],$codec["green"],$codec["blue"]);
						}
					}
				}

				imagecopymerge($this->source,$iris,$tl_x,$tl_y,0,0,$iris_w,$iris_h,100);
				imagedestroy($iris);	
			}
		}

		imagedestroy($eye_sprite);
		imagedestroy($source_copy);
	}

	private function searchBrush(){
		if(!$this->eyeTl || !$this->eyeTr || !$this->eyeBl){
			throw new Exception("Eyes not ready yet", 1);
		}

		$counter = 0;
		$brushs = array();
		$white = imagecolorallocate($this->source, 255,255,255);

		$clear_canvas = function()use($white){
			// on efface les modules standards
			// puis on recherche le plus petit module
			for ($x=0; $x < $this->sourceW; $x++) { 
				$p1 = null;
				$p2 = null;
				
				for ($y=0; $y < $this->sourceH ; $y++) { 
					
					// on evite les zone des yeux
					if(($x >= $this->eyeTl["data"][0][0] && $x <= $this->eyeTl["data"][1][0]) && ($y >= $this->eyeTl["data"][0][1] && $y <= $this->eyeTl["data"][3][1])) {
						continue;
					}

					if(($x >= $this->eyeTr["data"][0][0] && $x <= $this->eyeTr["data"][1][0])&&($y >= $this->eyeTr["data"][0][1] && $y <= $this->eyeTr["data"][3][1])){
						continue;
					}

					if(($x >= $this->eyeBl["data"][0][0] && $x <= $this->eyeBl["data"][1][0])&&($y >= $this->eyeBl["data"][0][1] && $y <= $this->eyeBl["data"][3][1])){
						continue;
					}
					imagesetpixel($this->source,$x,$y,$white);
				}
			}
		};

		$clear_canvas();
		
		// on recupere les coordonnées des brushs
		for ($x=0; $x < $this->sourceW ; $x++) { 

			$p1 = null;
			$p2 = null;
			$match = false;
			$brush = array();
			
			for ($y=0; $y < $this->sourceH ; $y++) { 

				// on evite les zone des yeux
				if(($x >= $this->eyeTl["data"][0][0] && $x <= $this->eyeTl["data"][1][0]) && ($y >= $this->eyeTl["data"][0][1] && $y <= $this->eyeTl["data"][3][1])) {
					continue;
				}

				if(($x >= $this->eyeTr["data"][0][0] && $x <= $this->eyeTr["data"][1][0])&&($y >= $this->eyeTr["data"][0][1] && $y <= $this->eyeTr["data"][3][1])){
					continue;
				}

				if(($x >= $this->eyeBl["data"][0][0] && $x <= $this->eyeBl["data"][1][0])&&($y >= $this->eyeBl["data"][0][1] && $y <= $this->eyeBl["data"][3][1])){
					continue;
				}

				$rgb = imagecolorat ($this->original_source ,$x ,$y );
				$colors = imagecolorsforindex($this->original_source, $rgb);


				if($colors['red'] == 0 && $colors['green'] == 0 && $colors['blue'] == 0){
					if(!$p1){
						$p1 = array("x"=>$x,"y"=>$y);
					}
				}
				else{
					if($p1){
						$p2 = array("x"=>$x,"y"=>$y-1);
						$ret = $this->renderBrush($p1,$p2,$brush);
						$counter+=count($ret);
						$match = true;
					}

					$p1 = null;
					$p2 = null;
				}
			}

			if($match){
				$brushs[] = $brush;
				$x += $this->moduleWidth;
				$match = false;
			}
		}

		return $counter;
	}

	private function renderBrush($p1,$p2,&$points){
		$w = $this->moduleWidth+1;

		$h = $p2["y"] - $p1["y"];
		$nbr = round($h/$w);

		$x = $p1["x"];
		$y = $p1["y"];
		
		$rest = $h - ($nbr*$w);
		if($rest>0)
			$nbr++;

		$coords = array();

		$brush_sprite = imagecreatefrompng(__DIR__."/../images/modules/ronds/grille_5.png");
		$even_sprite = imagecreatefrompng(__DIR__."/../images/modules/etoile/simple.png");
		$odd_sprite = imagecreatefrompng(__DIR__."/../images/modules/ronds/simple.png");


		for ($i=0; $i < $nbr; $i++) { 
			$tl_x = $x;
			$tl_y = ($y+($w*$i));
			$tr_x = $x+$w;
			$tr_y = $tl_y;
			
			$bl_x = $tl_x;
			$bl_y = $tl_y+$w;
			$br_x = $tr_x;
			$br_y = $bl_y;

			$point = array(
				array($tl_x,$tl_y),array($tr_x,$tr_y),
				array($br_x,$br_y),array($bl_x,$bl_y)
			);

			$brush = imagescale($brush_sprite,$w,$w);
			$brush_w = imagesx($brush);
			$brush_h = imagesy($brush);
			imagecopymerge($this->source,$brush,$tl_x,$tl_y,0,0,$brush_w,$brush_h,100);
			imagedestroy($brush);

			/*if($i%2){
				$even = imagescale($even_sprite,$w,$w);
				$brush_w = imagesx($even);
				$brush_h = imagesy($even);
				imagecopymerge($this->source,$even,$tl_x,$tl_y,0,0,$brush_w,$brush_h,100);
				imagedestroy($even);
			}
			else{
				$odd = imagescale($odd_sprite,$w,$w);
				$brush_w = imagesx($odd);
				$brush_h = imagesy($odd);
				imagecopymerge($this->source,$odd,$tl_x,$tl_y,0,0,$brush_w,$brush_h,100);
				imagedestroy($odd);
			}*/

			$coords[] = $point;
			$points[] = $point;
		}

		imagedestroy($brush_sprite);
		imagedestroy($even_sprite);
		imagedestroy($odd_sprite);

		return $coords;
	}

	

	private function renderLogo($logoW,$logoH){

		$firstEyeHeight = $this->eyeTl['height'];

		if($firstEyeHeight > $logoH){
			$logoW = round($firstEyeHeight/2);
			$logoH = round($firstEyeHeight/2);
		}
		
		$circleW = $logoW;
		$circleH = $logoH;


		// on cree le cercle
		$circle = imagecreatetruecolor($circleW, $circleH);
		$blanc_circle = imagecolorallocate ($circle, 255, 255, 255);
		$f44336 = imagecolorallocate ($circle, 244, 67, 54);

		$black = imagecolorallocate ($circle, 156, 156, 156);
		imagefill ($circle, 0, 0, $black);
		imagecolortransparent($circle, $black);
		//imagefilledellipse($circle,$circleW/2,$circleH/2, $circleW, $circleH, $blanc_circle);
		imagefilledrectangle ($circle ,0 ,0,$circleW, $circleH,$blanc_circle);

		//imagestring($circle,2,($circleW/2)-16,$circleH-17, "Dmoizi", $black);

		// on cree le logo
		$logo_wide = imagecreatefrompng($this->logo);
		$blanc_2 = imagecolorallocate ($logo_wide, 255, 255, 255);
		imagefill ($logo_wide, 0, 0, $blanc_2);
		imagecolortransparent($logo_wide, $blanc_2);

		$logo = imagecreatetruecolor($logoW, $logoH);
		$blanc_3 = imagecolorallocate ($logo, 255, 255, 255);
		imagefill ($logo, 0, 0, $blanc_3);
		imagecolortransparent($logo, $blanc_3);

		$l_source = imagesx($logo_wide);
		$h_source = imagesy($logo_wide);
		$l_dest = imagesx($logo);
		$h_dest = imagesy($logo);
		imagecopyresampled ($logo,$logo_wide, 0, 0, 0, 0,$l_dest, $h_dest, $l_source,$h_source);

		$sourceW = imagesx($logo);
		$sourceH = imagesy($logo);
		$destW = imagesx($circle);
		$destH = imagesy($circle);
		$sourceX = ($destW/2)-($sourceW/2);
		$sourceY = ($destH/2)-($sourceH/2);
		imagecopymerge($circle,$logo,$sourceX,$sourceY, 0, 0, $sourceW, $sourceH, 100);			
		$sourceW = imagesx($circle);
		$sourceH = imagesy($circle);
		$qrcodeW = imagesx($this->source);
		$qrcodeH = imagesy($this->source);

		$sourceX = ($qrcodeW/2)-($sourceW/2);
		$sourceY = ($qrcodeH/2)-($sourceH/2);

		/*if($this->options['logo']['creux']){
			$margeTl_x = $sourceX-$this->moduleWidth+1;
			$margeTl_y = $sourceY-$this->moduleWidth+1;
			$margeTr_x = $margeTl_x+$sourceW+$this->moduleWidth+1;
			$margeTr_y = $margeTl_y;
			$margeBr_x = $margeTr_x;
			$margeBr_y = $margeTr_y+$sourceH+$this->moduleWidth+1;
			$margeBl_x = $margeTl_x;
			$margeBl_y = $margeBr_y;
			imagefilledrectangle ($this->source ,$margeTl_x ,$margeTl_y ,$margeBr_x ,$margeBr_y ,$blanc_circle);
		}*/

		// On met le logo (source) dans l'image de destination (la photo)
		imagecopymerge($this->source, $circle, $sourceX,$sourceY, 0, 0, $sourceW, $sourceH, 100);	

		
		

		imagedestroy($logo);
		imagedestroy($logo_wide);
		imagedestroy($circle);
	}

	public function render(){
		$start_time = microtime(true);

		$eyes = $this->detectEyes();
		if($this->eye != QrCodeCustomization::EYE_CARRE){
			$this->searchBrush();
		}
		$this->renderEyes($eyes);
		$this->renderLogo(50,50);
		
		$end_time = microtime(true);
		$elapsed_time = $end_time - $start_time;

		//echo "<br> searchEyes: $elapsed_time";

		
		if(@$_GET['m'] == 1 && QrCodeCustomization::EYE_CARRE != $this->eye){
		  	echo '<pre>';
			print_r($eyes);
		  echo '</pre>';
		}
		else{
			ob_clean();

			if(in_array($_SERVER["HTTP_ACCEPT"],array("base64","b64"))) {
				header('Content-Type: base64');
				ob_start("ob_gzhandler");
				imagepng($this->source);
				$content = ob_get_clean();
				echo base64_encode($content);
			}
			else{
				header('Content-type: image/png');
				imagepng($this->source);
			}
		}
		imagedestroy($this->source);
		imagedestroy($this->original_source);
	}


	private function hex2RGB($hexStr) {
		// Gets a proper hex string
	    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); 

	    $rgbArray = array();
	    //If a proper hex code, convert using bitwise operation. No overhead... faster
	    if (strlen($hexStr) == 6) { 
	        $colorVal = hexdec($hexStr);
	        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
	        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
	        $rgbArray['blue'] = 0xFF & $colorVal;
	    } 
	    //if shorthand notation, need some string manipulations
	    elseif (strlen($hexStr) == 3) { 
	        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
	        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
	        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
	    } 
	    //Invalid hex color code
	    else {
	        return false; 
	    }
	    return $rgbArray;
	}
}
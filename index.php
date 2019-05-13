<?php
	use Aaz\QrCodeCustomization;

	$origins = array(
		"http://localhost/bandartists"
	);

	$origin = @$_SERVER["HTTP_ORIGIN"];

	if(in_array($origin, $origins)){
		header("Access-Control-Allow-Origin: $origin");
	}

	header("Access-Control-Allow-Headers:Authorization,X-URL");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Allow-METHODS: GET');

	if($_SERVER['REQUEST_METHOD'] == "OPTIONS"){
		header("HTTP/1.0 200 OK");
		exit();
	}
	
	require "autoload.php";

	$chl = isset($_GET['chl']) ? $_GET['chl'] : (isset($_POST['chl']) ? $_POST['chl'] : null);
	
	$choe = isset($_GET['choe']) ? $_GET['choe'] : (isset($_POST['choe']) ? $_POST['choe'] : null);
	
	$chld = isset($_GET['chld']) ? intval($_GET['chld']) : (isset($_POST['chld']) ? intval($_POST['chld']) : null);

	$chs = isset($_GET['chs']) ? intval($_GET['chs']) : (isset($_POST['chs']) ? intval($_POST['chs']) : null);


	$headers = array();

   	foreach($_SERVER as $key => $value) {
    	$partial = 0;
    	if (substr($key, 0, 5) == 'HTTP_'){
    		$partial = 5;
    	} 
    	else if(substr($key, 0, 14) == 'REDIRECT_HTTP_') {
            $partial = 14;
        }
        else
        	continue;
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, $partial)))));
        $headers[$header] = $value;
    }

    /*if(!isset($headers['Authorization']) || !trim($headers['Authorization'])){
    	ob_clean();
		header("HTTP/1.0 401 Unauthorized");
		exit();
	}*/


	// GESTION DE CACHE [DEBUT]

	if(isset($headers['If-None-Match'])){

		$isValid = false;
		try {
			$ifNoneMatch = $headers['If-None-Match'];
			$ifNoneMatch = explode(".",base64_decode($ifNoneMatch));
			@$ext = $ifNoneMatch[0];
			@$time = $ifNoneMatch[1];

			if($ext == $chl)
				$isValid = true;

			$start = new Datetime();
			$start->setTimestamp($time);
		} catch (Exception $e) {

		}

		if($isValid){

			$end = new Datetime();
			$diff = $start->diff($end);

			$jr = intval($diff->format('%R%a'));
			$h = intval($diff->format('%R%h'));
			$min = intval($diff->format('%R%i'));
			$sec = intval($diff->format('%R%s'));

			if($jr <= 30){
				header("ETag: $etag");
				header('Cache-Control: max-age=2592000');
				header("Expires: " . date(DATE_RFC822,$time+2592000));
				header("Pragma: cache");
			 	header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304); 
			 	header("X-Powered-By: AAZ Agency");
			 	goto end_all;
			  	exit; 
			}
		}
	}
	// GESTION DE CACHE [FIN]

	$qrcodeW = 300;
	$qrcodeH = 300;
	$file_path = "https://chart.googleapis.com/chart?cht=qr&chl=".urldecode($chl)."&choe=UTF-8&chs=".$qrcodeW."x".$qrcodeH."&chld=Q";
	

	if(!($content = @file_get_contents($file_path))){
		header("HTTP/1.0 404 Not Found");
		exit();
	}

	// on cree le qrcode
	$source = imagecreatefromstring($content);
	$customizer = new QrCodeCustomization($source);

	$eyesconfig = array();
	$irisconfig = array();


	if(@$_GET["eye"]){
		$eyesconfig["model"] = $_GET["eye"];
	}

	if(@$_GET["eye_bg"]){
		$eyesconfig["global"] = $_GET["eye_bg"];
	}

	if(@$_GET["eye_tl_bg"]){
		$eyesconfig["topLeft"] = $_GET["eye_tl_bg"];
	}
	if(@$_GET["eye_bl_bg"]){
		$eyesconfig["bottomLeft"] = $_GET["eye_bl_bg"];
	}
	if(@$_GET["eye_tr_bg"]){
		$eyesconfig["topRight"] = $_GET["eye_tr_bg"];
	}

	if(@$_GET["iris_bg"]){
		$irisconfig["global"] = $_GET["iris_bg"];
	}

	if(@$_GET["iris_tl_bg"]){
		$irisconfig["topLeft"] = $_GET["iris_tl_bg"];
	}
	if(@$_GET["iris_bl_bg"]){
		$irisconfig["bottomLeft"] = $_GET["iris_bl_bg"];
	}
	if(@$_GET["iris_tr_bg"]){
		$irisconfig["topRight"] = $_GET["iris_tr_bg"];
	}

	if(@$_GET["bg"]){
		$customizer->setOptions('bg',@$_GET["bg"]);
	}
	else if(empty($eyesconfig) && empty($irisconfig)) {

		$models = array(
			QrCodeCustomization::EYE_ARRONDI,QrCodeCustomization::EYE_ARRONDI_ROND,
			QrCodeCustomization::EYE_BOUCLIER,QrCodeCustomization::EYE_COUSSIN,
			QrCodeCustomization::EYE_DIAMANT,QrCodeCustomization::EYE_ETOILE,
			QrCodeCustomization::EYE_GALBE,QrCodeCustomization::EYE_GRILLE,
			QrCodeCustomization::EYE_OCTOGONE,QrCodeCustomization::EYE_OEIL_DROIT,
			QrCodeCustomization::EYE_OEIL_GAUCHE,QrCodeCustomization::EYE_POINTU,
			QrCodeCustomization::EYE_RONDS,QrCodeCustomization::EYE_SIMPLE_ROND,
			QrCodeCustomization::EYE_TRAMIS,QrCodeCustomization::EYE_VAGUE,
			QrCodeCustomization::EYE_FEUILLE,QrCodeCustomization::EYE_ROND,
			QrCodeCustomization::EYE_ROND_ETOILE,QrCodeCustomization::EYE_ROND_ETOILE_2,
			QrCodeCustomization::EYE_ROND_ETOILE_GAUFRE,
		);

		//$eyesconfig["model"] = $models[mt_rand(0,count($models))];
		//$eyesconfig["global"] = "313032";
		//$customizer->setOptions('bg',"111111");
		//$irisconfig["global"] = "D12E1F";*/

		$eyesconfig["model"] = $models[mt_rand(0,count($models))];
		$eyesconfig["global"] = "313032";
		$customizer->setOptions('bg',"111111");
		$irisconfig["global"] = "D12E1F";
	}

	$customizer->setOptions('eyes',$eyesconfig);
	$customizer->setOptions('iris',$irisconfig);
	$customizer->setLogo(__DIR__."/images/logo.png");


	$maxAge = 2592000;
	$time = time();
	$etag = base64_encode(implode('.', array($chl,$time)));

	header("ETag: $etag");
	header('Last-Modified: '.date(DATE_RFC822,$time),true,200); 
	header('Cache-Control: max-age=2592000');
	header("Expires: " . date(DATE_RFC822,strtotime("1 month")));
	header("Pragma: cache");

	header("X-Powered-By: AAZ Agency");
	header("X-Ratelimit-Requests-Limit: 1");
	header("X-Ratelimit-Requests-Remaining: 0");

	$customizer->render();

	end_all:
?>


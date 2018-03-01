<?php
function t2s($messageid, $MessageStorepath, $textstring, $filename)

// google: Erstellt basierend auf Input eine TTS Nachricht, �bermittelt sie an Google.com und 
// speichert das zur�ckkommende file lokal ab

{
	global $config, $messageid, $pathlanguagefile;
	
	$file = "google.json";
	$url = $pathlanguagefile."".$file;
	$valid_languages = File_Get_Array_From_JSON($url, $zip=false);
	
		if (isset($_GET['lang'])) {
			$language = $_GET['lang'];
			$isvalid = array_multi_search($language, $valid_languages, $sKey = "value");
			if (!empty($isvalid)) {
				$language = $_GET['lang'];	
			} else {
				trigger_error('The entered Google language key is not supported. Please correct (see Wiki)!', E_USER_ERROR);
				exit;
			}
		} else {
			$language = $config['TTS']['messageLang'];
		}	
		
		#####################################################################################################################
		# Zum Testen da auf Google Translate basierend (urlencode)
		# ersetzt Umlaute um die Sprachqualit�t zu verbessern
		# $search = array('�','�','�','�','�','�','�','�','%20','%C3%84','%C4','%C3%9C','%FC','%C3%96','%F6','%DF','%C3%9F');
		# $replace = array('ae','ue','oe','Ae','Ue','Oe','ss','Grad',' ','ae','ae','ue','ue','oe','oe','ss','ss');
		# $textstring = str_replace($search,$replace,$textstring);
		#####################################################################################################################
		
		if (strlen($textstring) > 100) {
            LOGGING("The Google T2S contains more than 100 characters and therefor could not be generated. Please reduce characters in your message!",3);
			exit;
        }
								  
		# Speicherort der MP3 Datei
		$mpath = $config['SYSTEM']['messageStorePath'];
		$file = $MessageStorepath . $filename . ".mp3";
		#$textstring = utf8_encode($textstring);
		
		#Generieren des strings der an Google geschickt wird.
		$inlay = "ie=UTF-8&total=1&idx=0&textlen=100&client=tw-ob&q=$textstring&tl=$language";	
					
		# Pr�fung ob die MP3 Datei bereits vorhanden ist
		if (!file_exists($file)) 
		{
			# �bermitteln des strings an Google.com
			$mp3 = file_get_contents("http://translate.google.com/translate_tts?".$inlay);
			file_put_contents($file, $mp3);
		}
	$messageid = $filename;
	return ($messageid);
}


?> 
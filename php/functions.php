<?php
class Site {
	public static function Alerta($texto, $location){
		if($location == false){
			$retorno = '<script>alert(" '.$texto.' ");</script>';
		}else{
			$retorno = '<script>alert(" '.$texto.' ");location.href="'.$location.'";</script>';
		}
		return $retorno;
	}

	public static function Url($texto){
		$texto = html_entity_decode($texto);
		$texto = @eregi_replace('[aÃƒÂ¡ÃƒÂ ÃƒÂ£ÃƒÂ¢ÃƒÂ¤]','a',$texto);
		$texto = @eregi_replace('[eÃƒÂ©ÃƒÂ¨ÃƒÂªÃƒÂ«]','e',$texto);
		$texto = @eregi_replace('[iÃƒÂ­ÃƒÂ¬ÃƒÂ®ÃƒÂ¯]','i',$texto);
		$texto = @eregi_replace('[oÃƒÂ³ÃƒÂ²ÃƒÂµÃƒÂ´ÃƒÂ¶]','o',$texto);
		$texto = @eregi_replace('[uÃƒÂºÃƒÂ¹ÃƒÂ»ÃƒÂ¼]','u',$texto);
		$texto = @eregi_replace('[ÃƒÂ§]','c',$texto);
		$texto = @eregi_replace('[ÃƒÂ±]','n',$texto);
		$texto = @eregi_replace('( )','-',$texto);
		$texto = @eregi_replace('[^a-z0-9\-]','',$texto);
		$texto = @eregi_replace('--','-',$texto);
		return strtolower($texto);
	}
}
?>
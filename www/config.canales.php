<?php
/**
canales 3TV
*/
if($_SERVER['HTTP_HOST'] == "videocms.copesa.cl" || $_SERVER['HTTP_HOST'] == "dev-www-3tv-cl.lab.codisa.cl"){
	//QA
	define("C_NOTICIAS",8);
		define("C_CHILE",9);
		define("C_BBC_MUNDO",57);
		define("C_CAMARA_HIPODERMICA",92);
		define("C_MUNDO",10);
		define("C_REPORTAJES",36);
		define("C_NEGOCIOS",48);
		define("C_INFLUENZA_HUMANA",50);
	define("C_GOLES", 38);
		define("C_RUMBO_AL_MUNDIAL", 52);
		define("C_CAMPEONATO_CHILENO", 39);
		define("C_CHILENOS_EN_EL_EXTERIOR", 43);
		define("C_GOLES_DEL_MUNDO", 49);
		define("C_RANKING_DE_GOLES", 40);
	define("C_DEPORTES", 12);
		define("C_YO_SOY_BONINI", 12);
		define("C_FUTBOL", 13);
		define("C_FORMULA_UNO", 124);
		define("C_CHILE_CLASIFICADO", 58);
		define("C_OTROS_DEPORTES", 15);
		define("C_MOTORES", 14);
		define("C_DAKAR_2010", 114);
	define("C_ENTRETENCION", 16);
		define("C_ZAPPING", 17);
		define("C_VINA_2010", 122);
		define("C_LACUARTATV", 54);
		define("C_CINE", 19);
		define("C_MUERTE_DE_JACKSON", 53);
		define("C_MUSICA", 23);
		define("C_CLUB_LA_TERCERA", 29);
		define("C_GUIA_DEL_OCIO", 28);
		define("C_SERIES", 18);
	define("C_TENDENCIAS", 42);
		define("C_WAIN", 125);
		define("C_TECNOLOGIA", 30);
		define("C_ESTILO_DE_VIDA", 24);
		define("C_ARTE_SIN_MUROS_TV", 51);
	define("C_TERREMOTO", 59);
	
	define("C_COPA_2010", 60);
	define("C_3TV_SUDAFRICA", 61);
	
}else{
	//Produccion
	define("C_NOTICIAS",8);
		define("C_CHILE",9);
		define("C_BBC_MUNDO",57);
		define("C_CAMARA_HIPODERMICA",92);
		define("C_MUNDO",10);
		define("C_REPORTAJES",36);
		define("C_NEGOCIOS",48);
		define("C_INFLUENZA_HUMANA",50);
	define("C_GOLES", 38);
		define("C_RUMBO_AL_MUNDIAL", 52);
		define("C_CAMPEONATO_CHILENO", 39);
		define("C_CHILENOS_EN_EL_EXTERIOR", 43);
		define("C_GOLES_DEL_MUNDO", 49);
		define("C_RANKING_DE_GOLES", 40);
	define("C_DEPORTES", 12);
		define("C_YO_SOY_BONINI", 12);
		define("C_FUTBOL", 13);
		define("C_FORMULA_UNO", 124);
		define("C_CHILE_CLASIFICADO", 58);
		define("C_OTROS_DEPORTES", 15);
		define("C_MOTORES", 14);
		define("C_DAKAR_2010", 114);
	define("C_ENTRETENCION", 39);
		define("C_ZAPPING", 17);
		define("C_VINA_2010", 122);
		define("C_LACUARTATV", 54);
		define("C_CINE", 19);
		define("C_MUERTE_DE_JACKSON", 53);
		define("C_MUSICA", 23);
		define("C_CLUB_LA_TERCERA", 29);
		define("C_GUIA_DEL_OCIO", 28);
		define("C_SERIES", 18);
	define("C_TENDENCIAS", 42);
		define("C_WAIN", 125);
		define("C_TECNOLOGIA", 30);
		define("C_ESTILO_DE_VIDA", 24);
		define("C_ARTE_SIN_MUROS_TV", 51);
	define("C_TERREMOTO", 123);
	define("C_COPA_2010", 127);
	define("C_3TV_SUDAFRICA", 128);
}	
?>
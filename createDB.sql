-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.67-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema votamostodos
--

CREATE DATABASE IF NOT EXISTS votamostodos;
USE votamostodos;

--
-- Definition of table `ley`
--

DROP TABLE IF EXISTS `ley`;
CREATE TABLE `ley` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `expediente` varchar(45) NOT NULL,
  `titulo_lleca` varchar(100) NOT NULL,
  `titulo_real` varchar(400) NOT NULL,
  `url_votamostodos` varchar(200) NOT NULL,
  `url_diputados` varchar(400) NOT NULL,
  `cant_votos_si` int(10) unsigned NOT NULL default '0',
  `cant_votos_no` int(10) unsigned NOT NULL default '0',
  `prioridad` int(10) unsigned NOT NULL,
  `activa` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ley`
--

/*!40000 ALTER TABLE `ley` DISABLE KEYS */;
INSERT INTO `ley` (`id`,`expediente`,`titulo_lleca`,`titulo_real`,`url_votamostodos`,`url_diputados`,`cant_votos_si`,`cant_votos_no`,`prioridad`,`activa`) VALUES 
 (1,'2251-D-2012','Transferencia de los subtes a la ciudad','TRANSFERENCIA DE LOS SERVICIOS DE TRANSPORTE DE PASAJEROS A LA CIUDAD AUTONOMA DE BUENOS AIRES. RATIFICACION POR PARTE DEL H. CONGRESO DE LA NACION DE LOS CONVENIOS QUE SE CELEBREN ENTRE EL PODER EJECUTIVO NACIONAL Y LA CIUDAD AUTONOMA DE BUENOS AIRES.','transferencia-de-los-subtes-a-la-ciudad','http://www1.hcdn.gov.ar/proyxml/expediente.asp?fundamentos=si&numexp=2251-D-2012',0,0,5,1),
 (2,'5246-D-2011','Muerte Digna','DERECHO DEL PACIENTE, HISTORIA CLINICA Y CONSENTIMIENTO INFORMADO - LEY 26529 -. MODIFICACIONES, SOBRE MUERTE DIGNA Y CUIDADOS PALIATIVOS INTEGRALES.','muerte-digna','http://www1.hcdn.gov.ar/proyxml/expediente.asp?fundamentos=si&numexp=5246-D-2011',0,0,4,1),
 (3,'6259-D-2011','Expropiacion de YPF','NACIONALIZACION DE HIDROCARBUROS; EXPROPIACION DE ACTIVOS Y ACCIONES DE REPSOL YPF; CREACION DE YACIMIENTOS PETROLIFEROS DEL PUEBLO; DEROGACION DE LA LEY 24145.','expropiacion-de-ypf','http://www1.hcdn.gov.ar/proyxml/expediente.asp?fundamentos=si&numexp=6259-D-2011',0,0,2,1),
 (4,'0981-D-2012','Despenalizacion de las drogas para consumo personal','REGIMEN CONTRA EL NARCOTRAFICO - LEY 23737; MODIFICACIONES SOBRE DESPENALIZACION DE LA TENENCIA DE DROGAS PARA USO PERSONAL.\r\nREGIMEN CONTRA EL NARCOTRAFICO - LEY 23737; MODIFICACIONES SOBRE DESPENALIZACION DE LA TENENCIA DE DROGAS PARA USO PERSONAL.','despenalizacion-de-las-drogas-para-consumo-personal','http://www1.hcdn.gov.ar/proyxml/expediente.asp?fundamentos=si&numexp=0981-D-2012',0,0,0,1),
 (5,'1218-D-2012','Despenalizacion del aborto','INTERRUPCION VOLUNTARIA DEL EMBARAZO: REGIMEN, DESPENALIZACION DEL ABORTO.','despenalizacion-del-aborto','http://www1.hcdn.gov.ar/proyxml/expediente.asp?fundamentos=si&numexp=1218-D-2012',0,0,1,1),
 (6,'2885-D-2012','Modificacion de la carta organica del Banco Central','CARTA ORGANICA DEL BANCO CENTRAL DE LA REPUBLICA ARGENTINA -LEY 24144-. MODIFICACION DEL ARTICULO 15, SOBRE ESTATUTO DEL PERSONAL.','modificacion-de-la-carta-organica-del-banco-central','http://www1.hcdn.gov.ar/proyxml/expediente.asp?fundamentos=si&numexp=2885-D-2012',0,0,3,1),
 (7,'8126-D-2010','Ley de identidad de genero','IDENTIDAD DE GENERO; REGIMEN PARA SU RECONOCIMIENTO Y RESPETO: MODIFICACION DE LAS LEYES 17132 Y 18248.','ley-de-identidad-de-genero','http://www1.hcdn.gov.ar/proyxml/expediente.asp?fundamentos=si&numexp=8126-D-2010',0,0,5,1);
/*!40000 ALTER TABLE `ley` ENABLE KEYS */;


--
-- Definition of table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_facebook` int(10) unsigned NOT NULL default '0',
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario`
--

/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;


--
-- Definition of table `voto`
--

DROP TABLE IF EXISTS `voto`;
CREATE TABLE `voto` (
  `id_ley` int(10) unsigned NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  `voto` tinyint(1) NOT NULL,
  `momento` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_ley`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voto`
--

/*!40000 ALTER TABLE `voto` DISABLE KEYS */;
/*!40000 ALTER TABLE `voto` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

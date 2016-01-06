<?php 
/*
 *	DEMONIO SERVIDOR
 *	Titulo: GATILLADOR DE SERVICIOS A CONSUMIR DEPENDIEDO DE UN CAMBIO DE ESTADO
 *	Macro Proceso: PRE-INGRESO
 *	Proceso: Documentos de ingreso
 *	Autor: Alex Reimilla C.
 *  Fecha: 08/05/2015
 */
 
	ini_set( "display_errors", "off" );
	
	include($_SERVER['DOCUMENT_ROOT'] . "/minsal/MySqli.php");
	require_once ($_SERVER['DOCUMENT_ROOT']. '/minsal/ws_sicex/definitions/config.php');
	require_once ($_SERVER['DOCUMENT_ROOT']. "/minsal/ws_sicex/Util/_packageUtil.php");
	require_once ($_SERVER['DOCUMENT_ROOT']. "/minsal/ws_sicex/DAO/DAOWebservice.php");
	require_once ($_SERVER['DOCUMENT_ROOT']. "/minsal/ws_sicex/functionGlobal/classConsumidorWsSicex.php");
	require_once ($_SERVER['DOCUMENT_ROOT']. '/minsal/ws_sicex/wservice/06_Pagos/consumidor/SNI.TR.PA.047.php');

	$daoWebservice = new DAOWebservice();

	class demond {
		public function __construct() {
		}
		 	
		public 	function getDemond(){
			$query 	= 	"select * from sicex_detalle_pago where ESTADOPAGO = 'DOPAC' and ENVIADO = 'N'";
	    	$conn 	= new MySQL;
	    	$result = $conn->consulta($query);
	    	while ($row=$conn->fetch_assoc($result)){
	    		$arr[] = $row;
	    	}
	    	$conn->dispose($result);
	    	$conn->cerrar_conexion();
	    	return $arr;
	    		    	
		}
		
		
		public function procesarDatosDemond(){
						
			$array=self::getDemond();
			if(!empty($array)){
					foreach($array as $valor){
						
						switch (true){
							
							case ($valor[TIPOPAGO] == 'DeclaracionIngreso'):
								//$row = $daoWebservice->getRowsDeclaracionIngresoByNumDocto($NumDocto);							
								$wsOut = new wsSNITRPA047();
								$wsOut->getSNITRPA047($row[ID_CUERPO], $errorArr, $array);
							break;			
							
							
							case ($valor[TIPOPAGO] == 'DocumentoIngreso'):
								$row = $daoWebservice->getRowsDocumentoIngresoByNumDocto($NumDocto);							
								$wsOut = new wsSNITRPA047();
								$wsOut->getSNITRPA047($row[ID_CUERPO], $errorArr, $array);
							break;											
										
						} // END SWITCH
				
						
					} // END FOREACH
			}
			
		} // END FUNCTION
		

	}
	
$demond=new demond();
$demond->procesarDatosDemond();
	
?>



<?

function Conectarse($db_host,$db_user,$db_password,$db_schema)
{
  /* Me conecto al servidor */
  if (! ($link=mysql_connect($db_host,$db_user,$db_password)))
  { 
    echo "Error conectandose a la BD. ";
    exit();
  }
  
  /* Selecciono la base que esta en el servidor */
  if (!mysql_select_db($db_schema, $link))
  {
    echo "Error seleccionando la BD. ";
    exit();
  }
  
  //mysql_set_charset('utf8',$link);

  /* Si esta todo bien, devuelvo el link de la conexion */
  return $link;
}

?>
<?php
namespace App\Helpers;

/**
*
* @version 0.1.0.0
 * @license Copyright Empresa 2020. Todos los derechos reservados.
 * @author Junior Milano - Desarrollador Web
* @overview Clase que ayuda a evaluar el tipo de retorno de la api
*
**/
class ResponseHelper
{
    private static $type_responses=['success'=>200,'error'=>500,'fail'=>400];
    private static $codification =[
   'application/json; charset=utf-8'
  ];
    /**
    * Constructor de la clase
    * @author Junior Milano <renshocontact@gmail.com>
    * @memberof ResponseHelper
    */
    public function __construct()
    {
    }

    /**
    * Función para obtener el el número de respuesta según lo pasado como parámetro
    * @author Junior Milano <renshocontact@gmail.com>
    * @return numeric
    * @memberof ResponseHelper
    */
    public static function getTypeResponse($type)
    {
        if (isset(self::$type_responses[$type])) {
            return self::$type_responses[$type];
        } else {
            return self::$type_responses['error'];
        }
    }

    /**
    * Función para retornar la codificación empleada en las respuestas a los clientes
    * @author Junior Milano <renshocontact@gmail.com>
    * @return numeric
    * @memberof ResponseHelper
    */
    public static function getCodification()
    {
        return self::$codification[0];
    }
}

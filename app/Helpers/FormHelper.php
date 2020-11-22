<?php

namespace App\Helpers;

/**
 *
 * @version 0.1.0.0
 * @license Copyright Empresa 2020. Todos los derechos reservados.
 * @author Junior Milano - Desarrollador Web
 * @overview Clase que tiene métodos genéricos de ayuda para formularios
 *
 **/
class FormHelper
{
    /**
     * Constructor de la clase
     * @author Junior Milano <renshocontact@gmail.com>
     * @memberof FormHelper
     */
    public function __construct()
    {
    }

    /**
     * Función para poner los valores del formulario en un arreglo asociativo, usado para validator mayormente y así evitamos los errores
     * @author Junior Milano <renshocontact@gmail.com>
     * @return numeric
     * @memberof FormHelper
     */
    public static function makeFields($destiny, $inputs, $optional = 0)
    {
        $final_array = [];
        foreach ($destiny as $key => $value) {
            if (isset($inputs->{$key}) && $inputs->{$key} !== null && $inputs->{$key} !== 'null') {
                $final_array[$key] = trim($inputs->{$key});
            } else {
                if ($optional == 0) {
                    $final_array[$key] = '';
                }
            }
        }

        return $final_array;
    }
}

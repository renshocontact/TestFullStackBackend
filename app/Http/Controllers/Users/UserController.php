<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;

/**
 *
 * @version 0.1.0.0
 * @license Copyright Empresa 2020. Todos los derechos reservados.
 * @author Junior Milano - Desarrollador Web
 * @overview Clase que interactúa con los usuarios, permite el CRUD
 *
 **/
class UserController extends Controller
{
    /**
     * Constructor de la clase
     * @author Junior Milano <renshocontact@gmail.com>
     * @memberof UserController
     */
    public function __construct()
    {
    }

    /**
     * Función para consultar usuarios, es una función de uso genérico para evitar código repetido
     * @author Junior Milano <renshocontact@gmail.com>
     * @return object
     * @memberof UserController
     */

    private function getUsers()
    {
        return  Users::orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->select(['id', 'first_name', 'last_name', 'user', 'email']);
    }

    /**
     * Función para consultar al usuario pasado como parametro
     * @author Junior Milano <renshocontact@gmail.com>
     * @param  Request  $request contiene el request proveniente del frontend
     * @param string $lang lenguaje indicado desde el frontend
     * @param numeric $id id del usuario a obtener
     * @return array
     * @memberof UserController
     */
    public function get(Request $request, $lang = 'en', $id)
    {
        if (trim($lang) !== '') {
            \App::setLocale($lang);
        }

        $user = $this->getUsers()->where('id', '=', $id)->first();

        if (!$user) {
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.users.not_found')]];
        }

        return ['status' => 'success', 'data' => $user];
    }

    /**
     * Función para consultar los usuarios de forma paginada, opcionalmente buscándolos según lo que contenga $find. Sólo los activos
     * @author Junior Milano <renshocontact@gmail.com>
     * @param  Request  $request contiene el request proveniente del frontend
     * @param string $lang lenguaje indicado desde el frontend
     * @param string $quantity cantidad de registros a retornar
     * @param string $page página que se desea obtener
     * @return array
     * @memberof UserController
     */

    public function paginate(Request $request, $lang = 'en', $quantity, $page)
    {
        if (trim($lang) !== '') {
            \App::setLocale($lang);
        }

        if (trim($quantity) === '' || !is_numeric($quantity) || trim($page) === '' || !is_numeric($page)) {
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.not_page_quantity')]];
        }

        $users = $this->getUsers()->limit($quantity)
            ->offset(($page - 1) * $quantity)->get();

        if (!$users || count($users) == 0) {
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.users.not_data')]];
        }

        return ['status' => 'success', 'data' => $users];
    }

    /**
     * Función para insertar nuevos usuarios
     * @author Junior Milano <renshocontact@gmail.com>
     * @param  Request  $request contiene el request proveniente del frontend
     * @param string $lang lenguaje indicado desde el frontend
     * @return array
     * @memberof UserController
     */

    public function insert(Request $request, $lang = 'en')
    {
        if (trim($lang) !== '') {
            \App::setLocale($lang);
        }

        $request = $request->instance();
        $content = $request->getContent();
        if (trim($content) === '')
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.input_not_provided')]];

        $content_object = json_decode($content);
        $credentials = [
            'first_name' => '',
            'last_name' => '',
            'user' => '',
            'email' => ''
        ];
        $inputs = [];
        if (isset($content_object->input))
            $inputs = $content_object->input;
        else
            $inputs = $content_object;

        $credentials = \App\Helpers\FormHelper::makeFields($credentials, $inputs, 1);

        $validator = \Validator::make($credentials, [
            'first_name' => 'required|min:1|max:190',
            'last_name' => 'required|min:1|max:190',
            'user' => 'required|min:1|max:190',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['status' => 'error', 'data' => ['message' => $errors->all()]];
        }

        //validamos la existencia del usuario, no debe existir
        if (Users::where('user', '=', trim($credentials['user']))->orWhere('email', '=', trim($credentials['email']))->first(['id'])) {
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.users.exist')]];
        }

        //insertamos
        Users::create($credentials);

        return ['status' => 'success', 'data' => ['message' => \Lang::get('messages.success_register')]];
    }

    /**
     * Función para actualizar el usuario indicado como parámetro
     * @author Junior Milano <renshocontact@gmail.com>
     * @param  Request  $request contiene el request proveniente del frontend
     * @param string $lang lenguaje indicado desde el frontend
     * @param numeric $id id del usuario a actualizar
     * @return array
     * @memberof UserController
     */

    public function update(Request $request, $lang = 'en', $id)
    {
        if (trim($lang) !== '') {
            \App::setLocale($lang);
        }

        $request = $request->instance();
        $content = $request->getContent();
        if (trim($content) === '')
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.input_not_provided')]];

        $content_object = json_decode($content);
        $credentials = [
            'first_name' => '',
            'last_name' => '',
            'user' => '',
            'email' => ''
        ];

        $inputs = [];
        if (isset($content_object->input))
            $inputs = $content_object->input;
        else
            $inputs = $content_object;

        $credentials = \App\Helpers\FormHelper::makeFields($credentials, $inputs, 1);

        $validator = \Validator::make($credentials, [
            'first_name' => 'required|min:1|max:190',
            'last_name' => 'required|min:1|max:190',
            'user' => 'required|min:1|max:190',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['status' => 'error', 'data' => ['message' => $errors->all()]];
        }

        //validamos la existencia del usuario previamente
        $driver = Users::where('id', '=', $id)->first(['id']);
        if (!$driver) {
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.users.not_found')]];
        }

        //validamos la existencia del usuario y correo
        $validation = Users::where('id', '!=', $id)
            ->where(function ($query) use ($credentials) {
                $query->where('user', '=', trim($credentials['user']))
                    ->orWhere('email', '=', trim($credentials['email']));
            })
            ->first(['id']);

        if ($validation) {
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.users.exist')]];
        }
        $driver->update($credentials);

        return ['status' => 'success', 'data' => ['message' => \Lang::get('messages.success_update')]];
    }

    /**
     * Función para eliminar el usuario indicado como parámetro
     * @author Junior Milano <renshocontact@gmail.com>
     * @param  Request  $request contiene el request proveniente del frontend
     * @param string $lang lenguaje indicado desde el frontend
     * @param numeric $id id del usuario a eliminar
     * @return array
     * @memberof UserController
     */

    public function delete(Request $request, $lang = 'en', $id)
    {
        if (trim($lang) !== '') {
            \App::setLocale($lang);
        }

        //validamos que el conductor exista
        $user = Users::where('id', '=', $id)->first(['id']);
        if (!$user) {
            return ['status' => 'error', 'data' => ['message' => \Lang::get('messages.users.not_found')]];
        }
        $user->delete();

        return ['status' => 'success', 'data' => ['message' => \Lang::get('messages.success_delete')]];
    }
}

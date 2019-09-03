<?php

namespace App\Helpers;

class Helper {

    //$key=> picture desde el formulario, $path=> va ser el path a guardar
    public static function uploadFile($key, $path) {
        request()->file($key)->store($path); //subir la imagen
        return request()->file($key)->hashName(); //retornar el archivo que ha guardado
    }
}
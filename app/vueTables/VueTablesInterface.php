<?php 

namespace App\VueTables;

interface VueTablesInterface {
    // $model -> es la tabla
    public function get($model, Array $fields, Array $relations = []);
}
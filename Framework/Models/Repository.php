<?php 

namespace Framework\Models;

abstract class Repository {

    public static string $table;

    public static abstract function create($data);

    public static abstract function find($key);

    public static abstract function getAll();

    public static abstract function findWhere($key, $value);

    public static abstract function update($id, $key, $data);

    public static abstract function delete($key);
}
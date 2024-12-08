<?php 

namespace Framework\Models;

abstract class Model {

    public abstract static function fromArray(array $data);
}
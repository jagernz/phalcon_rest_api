<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Drivers extends Model
{
    public function initialize()
    {
        $this->hasOne(
            "id",
            "Cars",
            "driver_id"
        );
    }
}
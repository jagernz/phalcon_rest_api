<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Cars extends Model
{
    public function initialize()
    {
        $this->belongsTo(
            "driver_id",
            "Drivers",
            "id"
        );
    }
}
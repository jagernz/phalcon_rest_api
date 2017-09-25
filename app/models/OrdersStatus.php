<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class OrdersStatus extends Model
{
    public function initialize()
    {
        $this->hasOne(
            "id",
            "Orders",
            "status_id"
        );
    }
}
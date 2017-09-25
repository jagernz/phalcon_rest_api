<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Orders extends Model
{
    public $status_id;

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function initialize()
    {
        $this->belongsTo(
            "status_id",
            "OrdersStatus",
            "status"
        );
    }
}
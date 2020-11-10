<?php

namespace App\Http\Dto;


abstract class AbstractDto
{
    public function toArray()
    {
        return get_object_vars($this);
    }
}

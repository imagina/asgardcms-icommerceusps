<?php

namespace Modules\Icommerceusps\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Icommerceusps extends Model
{
    use Translatable;

    protected $table = 'icommerceusps__icommerceusps';
    public $translatedAttributes = [];
    protected $fillable = [];
}

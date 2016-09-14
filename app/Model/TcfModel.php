<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TcfModel extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    
    protected $table = 'tcf';
    
    /**
     * The primary key associated with model
     * 
     * @var string
     */
    
    protected $primaryKey = 'TCFID';
    
    /**
     * If timestamped columns are not named like created_at and updated_at then set false
     * 
     * @var boolean
     */
    
    public $timestamps = false;
    
    
}

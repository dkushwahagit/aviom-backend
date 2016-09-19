<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientInteractionModel extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    
    protected $table = 'clientinteraction';
    
    /**
     * The primary key associated with model
     * 
     * @var string
     */
    
    protected $primaryKey = 'CIId';
    
    /**
     * If timestamped columns are not named like created_at and updated_at then set false
     * 
     * @var boolean
     */
    
    public $timestamps = false;
}

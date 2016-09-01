<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientMasterModel extends Model
{
    /**
     * The table associated with Model
     * 
     * @var string
     * 
     */
    protected $table = 'clientmaster';
    
    /**
     * The primary key associated with the Model
     * 
     * @var string
     */
    protected $primaryKey = 'CMId';
    
    /**
     * If timestamped columns are not named like created_at and updated_at then set false
     * 
     * @var boolean
     */
    
    public $timestamps = false;
}

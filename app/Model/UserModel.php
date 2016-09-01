<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    
    protected $table = 'users';
    
    /**
     * The primary key associated with the table name.
     * 
     * @var string
     */
    
    protected $primaryKey = 'UserId';
    
    /**
     * If timestamped columns are not named like created_at and updated_at then set false
     * 
     * @var boolean
     */
    
    public $timestamps = false;
    
    
}

?>
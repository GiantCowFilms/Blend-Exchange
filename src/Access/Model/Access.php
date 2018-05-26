<?php declare(strict_types = 1);

/**
 * BlendFile Class
 * 
 */

namespace BlendExchange\Access\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use \BlendExchange\Model\Traits\RandomId;

class Access extends Eloquent {

    use RandomId;

	protected function idSize(){
		return 12;
    }   
    
    public $incrementing = false;
    
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accesses';

    public $timestamps = false;

    protected $dates = [
        'date' => 'Y-m-d H:i:s',
    ];


   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

   protected $fillable = [

        'fileId',
        'message',
        'val'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */

    protected $visible = [
        'fileId',
        'id',
        'type',
        'val'
    ];

    public function blend()
    {
        return $this->belongsTo(BlendFile::class,'fileId','id');
    }
}

<?php declare(strict_types = 1);

/**
 * BlendFile Class
 * 
 */

namespace BlendExchange\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BlendFile extends Eloquent {
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blends';

   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

   protected $fillable = [

        'questionUrl',

    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */

    protected $visible = [

        'questionUrl'

    ];
}

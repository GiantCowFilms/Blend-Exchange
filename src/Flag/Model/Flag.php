<?php declare(strict_types = 1);

/**
 * Flag Class
 * 
 */

namespace BlendExchange\Flag\Model;

use BlendExchange\Blend\Model\BlendFile;
use BlendExchange\Model\Traits\RandomId;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Flag extends Eloquent {
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
    protected $table = 'flags';

    public $timestamps = false;

    static function create(string $fileId, string $offense, string $message, string $ipAddress)
    {
        $flag = new self();
        $flag->offense = $offense;
        $flag->message = $message;
        $flag->fileId = $fileId;
        $flag->ip = $ipAddress;
        return $flag;
    }

    public function blend()
    {
        return $this->hasOne(BlendFile::class,'fileId','id');
    }
}

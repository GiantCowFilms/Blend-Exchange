<?php declare(strict_types = 1);

/**
 * User Class
 * 
 */

namespace BlendExchange\User\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use BlendExchange\Model\Traits\RandomId;


class User extends Eloquent {

    use RandomId;

    public const NO_ROLE = 0;
    public const ADMIN_ROLE = 1;

	protected function idSize(){
		return 10;
    }
    public $incrementing = false;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

   protected $fillable = [

    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */

    protected $visible = [
        'id',
        'username',
        'stackId'
    ];

    static function create(string $stackId,string $username, string $stackToken)
    {
        $user = new self();
        $user->stackId = $stackId;
        $user->username = $username;
        $user->stackToken = $stackToken;
        $user->save();
        return $user;
    }

    public function blends()
    {
        return $this->hasMany(BlendFile::class,'owner','id');
    }

    // public function profile () {
    //     return $this->makeVisible('email');
    // }

    public function login (string $password) : bool
    {
        if ($this->account_type === 'password') {
            return password_verify($password,$this->password);
        } else {
            return true;
        }
    }

    public function getRolesAttribute() {
        $roles = [];
        $roles[] = 'user';
        if($this->role === 1) {
            $roles[] = 'admin';
        }
        return $roles;
    }

    public function getAccountTypeAttribute () {
        if ($this->email === null) {
            return 'partial';
        }
        if ($this->password === null) {
            return 'external';
        }
        return 'password';
    }
}

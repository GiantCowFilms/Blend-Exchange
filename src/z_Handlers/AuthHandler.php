<?php  declare(strict_types = 1);

namespace BlendExchange\Handlers;
use BlendExchange\Models\Access;
use BlendExchange\Blend\Model\BlendFile;
use BlendExchange\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BlendExchange\Exceptions\AuthenticationException;

class AuthHandler {

    protected $user = null;

    public function __construct (Request $req, Response $res)
    {
        $this->request = $req;
        $this->response = $res;
        $this->session = $this->request->getSession();
        if($this->session === null) {
            $this->session = new Session();
            $this->request->setSession($this->session);
        }
    }

    public function setUser ($user) {
        if($user instanceof User) {
            $this->session->set('auth_user',$user->id);
        } else {
            $this->session->remove('auth_user');
        }
    }

    public function getUser() {
        if ($this->user === null) {
            $userId = $this->session->get('auth_user');
            //Return user or null if not found
            $this->user = User::find($userId);
        }
        return $this->user;
    }

    public function isAuthenticated() {
        $user = $this->getUser();
        if ($user !== null) {
            return $this->session->get('is_authenticated') === true;
        }   
        return false;
    }

    public function verifyPassword($user,$password) {
        if(password_verify($user->password,$password)) {
            return true;
        } else {
            throw new \AuthenticationException('Password Verification Failed');
        }
    }

    public function signInWithFormData($data) {
        $this->verifyPassword($data);
    }

    public function loginUser($user,$password = null) {
        if(!$this->canLogin($user)) {
            throw new \AuthenticationException('Please setup your account before logging in.');
        }
        if ($user->admin !== 0 || $user->password !== null) {
            $this->verifyPassword($user,$password);
        }
        $this->setUser($user);
        $this->session->set('is_authenticated',true);
    }

    public function logoutUser() {
        $this->setUser(null);
        $this->session->set('is_authenticated',false);
        $this->session->clear();
    }

    public function getAuthenticatedUser() {
        if($this->isAuthenticated()) {
            return $this->getUser();
        }
        return null;
    }

    /**
     * Checks if the users account meets the requirement to allow logging in.
     * @param User $user
     */
    public function canLogin($user) {
        return (
            filter_var($user->email, FILTER_VALIDATE_EMAIL) && 
            ($user->admin === 0 || $user->password !== null)
        );
    }
}
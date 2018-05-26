<?php declare(strict_types = 1);

namespace BlendExchange\Access;

use BlendExchange\Access\Model\Access;
use Symfony\Component\HttpFoundation\Request;
use BlendExchange\Input\IpAddress;
final class AccessManager 
{
    public function __construct () {

    }

    private function createAccess(string $type, string $id, string $ipAddress) : Access
    {
        //Refactor this out someday
        $ip = new IpAddress($ipAddress);
        $access = new Access();
        $access->type = $type;
        $access->ip = $ip->getAnonymized();
        $access->fileId = $id;
        return $access;
    }

    public function view(string $id,string $ipAddress) : void
    {
        $view = $this->createAccess('view',$id,$ipAddress);
        $view->save();
    }

    public function download(string $id,string $ipAddress) : void
    {
        $download = $this->createAccess('download',$id,$ipAddress);
        $download->save();
    }
}
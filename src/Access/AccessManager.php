<?php declare(strict_types = 1);

namespace BlendExchange\Access;

use BlendExchange\Access\Model\Access;
use Symfony\Component\HttpFoundation\Request;
use BlendExchange\Input\IpAddress;
use BlendExchange\Blend\Model\BlendFile;

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

    public function view(BlendFile $blend,string $ipAddress) : void
    {
        $view = $this->createAccess('view',$blend->id,$ipAddress);
        $view->save();
        $blend->updateViewCache();
        $blend->save();
    }

    public function download(BlendFile $blend,string $ipAddress) : void
    {
        $download = $this->createAccess('download',$blend->id,$ipAddress);
        $blend->updateDownloadCache();
        $blend->save();
        $download->save();
    }
}
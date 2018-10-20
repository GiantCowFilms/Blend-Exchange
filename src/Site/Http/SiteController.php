<?php declare(strict_types = 1);

namespace BlendExchange\Site\Http;

use BlendExchange\Api\ApiResponseFactory;
use BlendExchange\Filesystem\Storage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class SiteController
{

    public function __construct (
        ApiResponseFactory $api,
        Twig_Environment $view
    ) {
        $this->api = $api;
        $this->view = $view;
    }
   /**
     * The page used for vue components
     */
    public function dynamicPage() {
        return new Response($this->view->render('Pages/dynamic_page.twig'));
    }

    public function authPage() {
        return new Response($this->view->render('Pages/auth_page.twig'));
    }

    public function privacyPolicy() {
        return new Response($this->view->render('Pages/privacy_page.twig'));
    }

    public function termsOfService () {
        return new Response($this->view->render('Pages/tos_page.twig'));
    }

    public function about (\Google_Service_Drive $google_drive_service, \Stash\Pool $cache) {
        $item = $cache->getItem('About/Stats');
        if ($item->isMiss()) {
            $about = $google_drive_service->about->get([
                'fields' => 'storageQuota'
            ]);
            
            $quota = $about->getStorageQuota();
            $data = [
                'remaining' => ($quota->getLimit() - $quota->getUsageInDrive())/1024/1000/1000,
                'used' => $quota->getUsageInDrive()/1024/1000/1000,
                'total' => $quota->getLimit()/1024/1000/1000,
                'percent' => ($quota->getUsageInDrive() / $quota->getLimit()) * 100
            ];

            $item->set($data);
            $item->expiresAfter(3600 * 12); //Expires after half a day
            $cache->save($item);
        } else {
            $data = $item->get();
        }

        return $this->api->jsonResponse($data);
    }

    public function endpointNotFound(Request $request) : Response
    {
        return $this->api->notFoundResponse();
    }

    public function maintenance_mode(Request $request, Storage $storage) : Response {
        $message = json_decode($storage->get('/site/downfile')->read())->message;
        return new Response($this->view->render('Pages/down.twig',[
            'message' => $message
        ]));
    } 
}
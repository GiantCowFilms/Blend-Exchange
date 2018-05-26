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

    public function about (\Google_Service_Drive $google_drive_service) {
        $about = $google_drive_service->about->get([
            'fields' => 'storageQuota'
        ]);
        $quota = $about->getStorageQuota();
        return new Response($this->view->render('Pages/about_page.twig',[
            'remaining' => ($quota->getLimit() - $quota->getUsageInDrive())/1024/1000/1000,
            'used' => $quota->getUsageInDrive()/1024/1000/1000,
            'total' => $quota->getLimit()/1024/1000/1000,
            'percent' => ($quota->getUsageInDrive() / $quota->getLimit()) * 100
        ]));
    }

    public function contribute () {
        return new Response($this->view->render('Pages/contribute_page.twig'));
    }

    public function userscript () {
        return new Response($this->view->render('Pages/userscript_page.twig'));
    }

    public function help () {
        return new Response($this->view->render('Pages/help_page.twig'));
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
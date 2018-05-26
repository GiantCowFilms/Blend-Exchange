<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

/**
 * Setup Request
 */

$injector->alias('HttpFoundation\Request', 'Symfony\Component\HttpFoundation\Request');
$injector->share('Symfony\Component\HttpFoundation\Request');
$injector->define('HttpFoundation\Request', [
    ':query' => $_GET,
    ':request' => $_POST,
    ':attributes' => [],
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);


/**
 * Setup Response
 */

$injector->alias('HttpFoundation\Response', 'Symfony\Component\HttpFoundation\Response');
$injector->share('Symfony\Component\HttpFoundation\Response');

/**
 * Setup Logger
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$injector->delegate('Monolog\Logger',function () use ($injector,$config) {
    $logger = new Logger('BLEND-EXCHANGE');
    $logger->pushHandler(new StreamHandler($config['main_log'], Logger::WARNING));
    return $logger;
});

$injector->share('Monolog\Logger');




/**
 * Setup Twig (View Templates)
 */
$injector->define('Twig_Loader_Filesystem',[
    ':paths' => __DIR__.'/Views',
]);
$injector->define('Twig_Environment',[
    'loader' => 'Twig_Loader_Filesystem',
    ':options' => [
        'cache' => __DIR__.'/Views/.cache',
        'debug' => ($config['environment'] === 'development')
    ]
]);
$injector->share('Twig_Environment');

/**
 * Setup Google Drive
 */
 $injector->define('Google_Client',[
     ':config' => $config['google_drive']
 ]);

 $injector->share('Google_Client');
 $google_client = $injector->make('Google_Client');
 $google_client->setAccessToken(json_encode([
    'access_token' => $config['google_drive']['access_token'],
    'token_type' => 'Bearer',
    'expires_in' => 3600,
    'refresh_token' => $config['google_drive']['refresh_token'],
    'created'=> 1424627698
 ]));

$injector->share('Google_Service_Drive');

/**
 * Setup GoogleDrive Flysystem
 */

$injector->define(\Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter::class,[
    ':root' => '/blend-exchange'
]);

$injector->define(\League\Flysystem\Filesystem::class,[
    'adapter' => \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter::class
]);


/**
 * Setup Resources Flysytem
 */

$injector->define(BlendExchange\Filesystem\Resources::class,[
    ':adapter' => new \League\Flysystem\Adapter\Local(__DIR__ . '/../resources')
]);


/**
 * Setup Storage Flysystem
 */

$injector->define(BlendExchange\Filesystem\Storage::class,[
    ':adapter' => new \League\Flysystem\Adapter\Local(__DIR__ . '/../storage')
]);

// $injector->delegate('BlendExchange\Filesystem\Storage',function () use ($injector) {
//     return $injector->make(\League\Flysystem\Filesystem::class,[
//         'AbstractAdapter' => 'BlendExchange\Filesystem\StorageAdapter'
//     ]);
// });

/**
 * Setup StackExchange Client
 */
$injector->define('BlendExchange\Client\StackExchangeClient',[
    ':clientId' => $config['stackexchange']['client_id'],
    ':clientSecret' => $config['stackexchange']['client_secret'],
    ':key' => $config['stackexchange']['key']
]);

/**
 * Setup oauth
 */
$injector->define(AlexMasterov\OAuth2\Client\Provider\StackExchange::class,[
    ':options' => [
        'clientId'     => $config['stackexchange']['client_id'],
        'clientSecret' => $config['stackexchange']['client_secret'],
        'redirectUri'  => $config['stackexchange']['redirect_uri'],
        'state'        => '',
        'key'          => $config['stackexchange']['key'],
        'site'         => 'blender',
    ]
]);
$injector->share('AlexMasterov\OAuth2\Client\Provider\StackExchange');

/**
 * Setup PHP Stash
 */

$injector->define('Stash\Driver\FileSystem',[
    array()
]);
$injector->define('Stash\Pool',[
    'driver' => 'Stash\Driver\FileSystem'
]);

$injector->share('Stash\Pool');

/**
 * Setup JWT
 */

$injector->alias(Lcobucci\JWT\Signer::class,'Lcobucci\JWT\Signer\Hmac\Sha384');

/**
 * Setup Current User Factory
 */

$injector->define(BlendExchange\Authentication\Token\UserAuthenticationTokenParser::class, [
    ':key' => $config['app_key']
]);

$injector->define(BlendExchange\Authentication\Token\UserAuthenticationTokenGenerator::class, [
    ':key' => $config['app_key']
]);

$injector->delegate(BlendExchange\Authorization\User::class, function () use ($injector): BlendExchange\Authorization\User {
    $factory = $injector->make(BlendExchange\Authorization\CurrentUserFactory::class);
    return $factory->create();
});

/**
 * Setup Stateless Tokens
 */

use BlendExchange\Authentication\Token;

$injector->define(Token\JwtTokenGenerator::class, [
    ':key' => $config['app_key']
]);

$injector->define(Token\JwtTokenParser::class, [
    ':key' => $config['app_key']
]);


$injector->alias(Token\StatelessToken::class, Token\JwtToken::class);
$injector->alias(Token\StatelessTokenGenerator::class, Token\JwtTokenGenerator::class);
$injector->alias(Token\StatelessTokenParser::class, Token\JwtTokenParser::class);
$injector->alias(Token\StatelessTokenValidator::class, Token\JwtTokenValidator::class);

$injector->alias(Token\StatelessTokenFactory::class, Token\JwtTokenFactory::class);
/**
 * Setup Transformers
 */
//PaginatorInterface
 $injector->alias(League\Fractal\Pagination\PaginatorInterface::class, League\Fractal\Pagination\IlluminatePaginatorAdapter::class);


/**
 * Setup Email
 */
$injector->define(Omnimail\AmazonSES::class,[
    ':accessKey' => $config['email']['aws_id'],
    ':secretKey' =>  $config['email']['aws_key'],
    ':host'  =>  $config['email']['aws_region']
]);

$injector->alias(Omnimail\MailerInterface::class,Omnimail\AmazonSES::class);
$injector->share(Omnimail\AmazonSES::class);
return $injector;
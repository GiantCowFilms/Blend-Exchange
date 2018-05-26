<?php

// This file is to draft potential API uses


$userApi = $stackClient->getUserApi();

$filter = $stackClient->getFilterApi()->create(new FilterParameters());
$query = $userApi->queryBuilder('blender',$filter)->setOrderBy();
$posts = $userApi->getPosts($query);

$posts->getPage(1);

foreach($posts as $page) {
    $page->first();
    foreach($page as $post) {
        if('blend-exchange' === $post->getBody()) 
        {

        }
    }
}

/**
 * Get user from access token
 */

$stackClient = $stackExchangeClientFactory->createAuthenticatedClient($accessToken);
$userApi = $stackClient->getUserApi();
$me = $userApi->getMe(
    $userApi->queryBuilder('blender',$stackClient->filter())->query()
);
$me->getAccountId();

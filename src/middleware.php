<?php
// Application middleware


$app->add(function ($request, $response, $next) use ($app) {
    $this->get('renderer')->addAttribute('deployJs', $this->get('settings')['deployJs']);
    $response = $next($request, $response);
    return $response;
});
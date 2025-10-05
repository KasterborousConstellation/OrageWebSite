<?php
namespace App\Utils;

use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class RedirectUtils {
    public static function returnToSender(Request $request) : RedirectResponse{
        try{
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }catch (\TypeError $e){
            return new RedirectResponse('/');
        }
    }
}

<?php
namespace App\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class RedirectUtils {
    public static function returnToSender(Request $request) : RedirectResponse{
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}

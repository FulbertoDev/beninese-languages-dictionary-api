<?php



namespace App\Helpers;

/**
 * Class AuthErrors.
 */
class AuthorizedUserAgents
{
    const dart_3_8 = 'Dart/3.8 (dart:io)';
    const dart_3_10_4 = 'Dart/3.10.4 (dart:io)';

    const browser = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36';
    const authorizedOrigin = 'https://mdico.iamyourclounon.bj';


    const authorizedUserAgents = array(
        self::dart_3_8,
        self::dart_3_10_4,
        self::browser,
    );



}

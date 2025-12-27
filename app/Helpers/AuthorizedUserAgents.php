<?php



namespace App\Helpers;

/**
 * Class AuthErrors.
 */
class AuthorizedUserAgents
{
    const dart_3_8 = 'Dart/3.8 (dart:io)';
    const dart_3_10_4 = 'Dart/3.10.4 (dart:io)';


    const authorizedUserAgents = array(
        self::dart_3_8,
        self::dart_3_10_4
    );



}
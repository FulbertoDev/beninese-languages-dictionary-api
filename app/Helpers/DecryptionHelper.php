<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;

class DecryptionHelper
{
    // La clé en UTF-8 directement (32 caractères pour AES-256)
    private const SECRET_KEY = '3eac39f01b412349013485484f6c200c';
    // IV en UTF-8 (16 caractères)
    private const IV = 'b598433e3dc70d8e';

    public static function decryptUrlData(string $encryptedData): ?array
    {
        try {
            // Convert from URL-safe base64
            $base64 = str_replace(['-', '_'], ['+', '/'], $encryptedData);
            $padding = strlen($base64) % 4;
            $paddedBase64 = $padding ? $base64 . str_repeat('=', 4 - $padding) : $base64;

            // Utiliser directement la clé comme chaîne UTF-8
            $key = self::SECRET_KEY;
            // Compléter l'IV avec des zéros si nécessaire
            $iv = str_pad(self::IV, 16, "\0");

            // Décoder le base64
            $decoded = base64_decode($paddedBase64, true);
            if ($decoded === false) {
                Log::error('Échec du décodage base64');
                return null;
            }

            // Décrypter en utilisant AES-256-CBC
            $decrypted = openssl_decrypt(
                $decoded,
                'AES-256-CBC',  // Changé à 256 bits
                $key,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($decrypted === false) {
                Log::error('Échec du décryptage openssl: ' . openssl_error_string());
                Log::error('Détails:', [
                    'key_length' => strlen($key),
                    'iv_length' => strlen($iv),
                    'data_length' => strlen($decoded)
                ]);
                return null;
            }

            $result = json_decode($decrypted, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Erreur de décodage JSON: ' . json_last_error_msg());
                return null;
            }

            return $result;
        } catch (Exception $e) {
            Log::error('Exception lors du décryptage: ' . $e->getMessage());
            return null;
        }
    }
}

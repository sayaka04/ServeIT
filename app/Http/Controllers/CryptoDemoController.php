<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class CryptoDemoController extends Controller
{
    public function encrypt(string $data)
    {
        $encrypted = Crypt::encryptString($data);

        return response()->json([
            'original'  => $data,
            'encrypted' => $encrypted,
        ]);
    }

    public function decrypt(string $data)
    {
        try {
            $decrypted = Crypt::decryptString($data);

            return response()->json([
                'encrypted' => $data,
                'decrypted' => $decrypted,
            ]);
        } catch (DecryptException $e) {
            return response()->json([
                'error' => 'Invalid or corrupted encrypted data',
            ], 400);
        }
    }

    /**
     * Encrypt and decrypt in one request
     * (Before → Encrypted → After)
     */
    public function demoAES256(string $data)
    {
        try {
            $original = $data;

            // ENCRYPT
            $encrypted = Crypt::encryptString($original);

            // DECRYPT AGAIN
            $decrypted = Crypt::decryptString($encrypted);

            return response()->json([
                'before'   => $original,
                'encrypted' => $encrypted,
                'after'    => $decrypted,
                'status'   => $original === $decrypted ? 'success' : 'failed',
            ]);
        } catch (DecryptException $e) {
            return response()->json([
                'error' => 'Encryption or decryption failed',
            ], 400);
        }
    }
}

<?php
namespace App\Services\Api;

use App\Models\HealthUserDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;

class OvdCareService
{
    private $clientId = 'DIGIBIMA';
    private $password = 'DIGIBIMA@1234';
    private $passphrase = 'Bill ran from the giraffe toward the dolphin';

    private $publicKey = "-----BEGIN PUBLIC KEY-----\n"
        . "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAil0bMVjCH6OZF1aX0bcz\n"
        . "3utxfkT0tysBm7DBlnZMVWDIyR+3aJPLPEGJ6GuQ1LO9GUj3GnH/BMDlrg3Zv6LB\n"
        . "pAaSREnmxVg9XP4z449RBAO8DjrK5k/sZchjdBioJEXz66lVce+xKFxC4NGJ33gF\n"
        . "TSYu3PkVm4PcPkpRKwqJLbPiACBJ/VeYRFkJ+jF4c2c4k6QRY1OLxlG7KmYvUuzn\n"
        . "a6w74AkZlpv3+Sma8xmJoYZCruK8lZIidYdG3qBC8nSbxTRTZ2ReMIo8dfhD2YRu\n"
        . "8IozE86xnTW5PRV3/by2ZTJByvFPMDLahseAVSjBz1buztqOdHxbWCJgpZiDR52Y\n"
        . "qvALj90dCour1LtIRd9K/IS25fix3ZuF45UuUbhEd8bhMaoE5c2K6vVjTsB3Vs4p\n"
        . "/9hc/18ACZVC4/QyAQ4RQkfhrLcejZesyx5t5Ak/DShmHHNGCZ4I3zkMbPP9Db8j\n"
        . "YXviZoW7HpfQU0VM0ziSPXzu+POxjxlRIpxczV6zcAnDIZVJpu9n/qYF3af0IgwB\n"
        . "Jmqb+a7aONNVbw/ZIDMUe0bbbF3dxGkdHw3oRa78zJFV8rkTIQWTSCtQiAAlwF0w\n"
        . "TNvktgwipCuaeBWB2kNbGRMn/MKkDM6LfXQBX735kcnbY8Sr3C7oRA+VU1Upuq1s\n"
        . "RcCFc+dgMziReQmd5o4EHacCAwEAAQ==\n"
        . '-----END PUBLIC KEY-----';

    private $privateKey = "-----BEGIN ENCRYPTED PRIVATE KEY-----\n"
        . "MIIJnDBOBgkqhkiG9w0BBQ0wQTApBgkqhkiG9w0BBQwwHAQIjL+AI1pkOXECAggA\n"
        . "MAwGCCqGSIb3DQIJBQAwFAYIKoZIhvcNAwcECHfKhcau/RYeBIIJSIU8JYnvZlso\n"
        . "4bM6k7k2rt7GgiN9I5iooAjZ6pZ626Rg4GwbQFwVPN9vWd3BQy4NorwW16wEEIQA\n"
        . "iyUUSFL8wFxAZV+20WZEOkWEvOpXwwGwmz40HBB/UURTS2Wh5TvYVLOjqvzI4BoI\n"
        . "50+RqcnB5nzYRf6yLo5mpdAco+XlQF6Zp9JguYpuIdiFOTgdAAa3OhmA/sfO4YBN\n"
        . "AEEcqpd/MskKLhQxf5XueG4AqY/WGEFdgdVmES4YP7ZnmPP+hkb5zLkyy45uipQs\n"
        . "70GbX2peOD+B7ED+kNFmIWZLpRNOdOAdfNohdfPam2/xzjQ7AAISqwr+yr6UOiJp\n"
        . "Z3txl5xa4XsbZ7qo50NZZOCq92y63zPWQaiM507jYRpgqIMTHNlK6uTNfdbkyvbX\n"
        . "D5GGWAMBOVf0ZjpkTP5Gjck4CUL6xOUTtQwsOhWiNsBpFxH289LBLJTe3HAxxScC\n"
        . "sAftEeYneWz8hO/xy0ytdgKHNDJ+51PAh/72v7kdJLB99/PEtVNiANqYUo9tlxb0\n"
        . "Jx20didz9O+zO73OvI5LCmDqc3kgK5aGhDxFrMUMs+F5LE4tGbjVjZKdivJYWdlN\n"
        . "tf2rgY694ynD6bfXBrsCoC3qaiVFkxh2bGZKGqSFaUwx6bsaxDq+JV0j+NJq0ykn\n"
        . "Z/xTEPEaxRz3imUczLZOgZB0jHpzW576zK/I0yrS2pPvXG7uoNXkFFlRYFgX30XM\n"
        . "lyIzz3kCNbG4wO4v9AFglnsr3nODAuXlk4nFOyRS8cSuv8G5Ba9qYo66d3i86Rs2\n"
        . "enzyJav4RjivTHhbAkgJh6ihLlSOR280+HFY5LVCpjNHgouHL3LN303M6Lua3BCI\n"
        . "r8hQFqZzlgMQuJ5w9hdo37CChUuO5fVOmHU+JjZJ1DAf27glgtQHmbHn4EV4mFa6\n"
        . "vpXdEOROXsRIj4qtccjG791sFKes3zHNskTDuyEIOndNb5RpAcO0NKoOegfKRZLs\n"
        . "aRYO68tyqd7VbPGxsCcQ3DnYQMz9FdH19NoT9/9btu9/z8FYCNFzGk5my/hMq20i\n"
        . "vIfA4vySZ2TKj4CU4tKi3sIei+WKa6O4ieRMY/m1hZ+a7pYdwvXjOSHdyM4Ybqc8\n"
        . "6ZVH0cnem1tq+XzPclTuvrIjmR/s77LBp/LVMY1gLBReyaHbFmfzQppLwpK5eofa\n"
        . "pTfF0OiS+yG6l1X4W18aIscJWdMeA7Cav8YNOKtc6WWNYnEsqmt4XHaV4H78s8mW\n"
        . "i4kly+mLJfQFXkqDJHShqsjbR9+cJuyGiV+bEL3vMu9JRwJPqF4v1JykQFASRkcc\n"
        . "b8PVfege5Fav0eB/5NxYZO6cdFCzt5WVyt7t1UhlYoico0fqnxGSjN3N4HMK9JAz\n"
        . "zn2fg8VxYKQuo2ck6pVxkzjvM3XC3SBrN6uqp9YSsXydU1hmFGBk3CWwGE5cjzX6\n"
        . "X60P5pPxBeKqQZEkWSAEszjYg5C6p/DUjuEawBjAVzLYmCLSNAK8gQ6Zd1WZl8wf\n"
        . "OaQWZ7jcrApWkKK19HPRb4rFTFtUBv4ZcgkfD2D8UYSrYh412SVnR+JgsoLzV6gR\n"
        . "YLCMzJTxHRk1AkmEMnN3I5eKwLX3iblUIBGUWUzPnFZxqG6+en9jI1vce8zMnp8B\n"
        . "1Bca5r2lFu2NfUmE6X1Yo8VJ8nOaKeZcNMrsDTd2vKFHK8XMw798EHA2znSvh0ny\n"
        . "xDbNGnZKJTa40B7K0qRntSC5CMCtG8IlNh42VUbtBTisTc1uFPjWWpuxRg6AzHS2\n"
        . "6A0a2T4Gbbeg0yAQFBovOewRmZxQwEaXzZgWLO7e3pUe7Uh1//RBnKHQYO/bJcRC\n"
        . "Y/q5uMeeXt5NJJHh7K6l0orlH2PHv9I6z+7UfI2gvmNecGtC4BR9kb7h/0GSM7wc\n"
        . "pKbgWRtK3/hfq1SurfKjnrdFWXndXpwpXoIVh1SM59urwrot6UpJPU3bLFPyKbre\n"
        . "Zl7yZ86ThuWngpulHon1DGTGKQD5YKVNQumxPDm2uJHr7EomnJ/ycZL7ux+6dl2m\n"
        . "M3uyQRWTGsNvpifo1UvFYtoB9bPVIbU0RhpjaENRZZiV5OY1laAfy8wVhD/bwm3o\n"
        . "DaMMhG66VwU5crLQdI2RNlKG2dti8UuJNlIHYzI70BzyrIlZLDbkd/1smsCLL0QG\n"
        . "wQ3YOyBN2/xOxf8GIGax4BhjfshoEDhmSAJsjTjt+4/qnlixwqTpXjyG2YfIqrdn\n"
        . "o/YXWZGhJjmpeHbIVd1WZMaJfqVssn+hVhYKPBSCPmtiG7h+PIlZW/rbbmHWrM3j\n"
        . "f2OihlZU6gz/j/J9QLpBT9nFcKUGgZAfwv0aQBhczhSLXfqQeRyo9x1x0AAK39Vl\n"
        . "OTbgUNzjGMrL6rpFWlVJ+Cp2rPJHzH4CJDZl8B4d6QX54Vp3d3JwhqKIRvdVNPAz\n"
        . "MKgvmW2EfvhD8D6QBSS0ObJwj5Awv7br0yUe+6/iffzUnG05yTQY+naWnGliCBgX\n"
        . "qicH9UzQZala7CxjCiwFWDpkwc6zcz7BPhLUDhfo0w/MC5Llgm+O06n4NbV8jYig\n"
        . "tDuz1lyB/iki5I9YpkNONUcaqU29TkH8P4THNE0XyG9x0wXiC29tvfPgtvT89xNQ\n"
        . "XZ8T5XiordMYUH4FD2hUIl7mHKxbqLaqgxqfNulpPaflk8N6rxa4h8JnZR3UPtjY\n"
        . "tM5EEuvs95xPSdQBam0r/zdf6lT7MGE9+pkY6cOkRFePMadMJAiC9XRsU2rqlcnf\n"
        . "vGG87NV9EDYkY0udn18rTv47aTempfimwYhQ6IQMtOYoYCORhkf7SUrV9QHi/jqr\n"
        . "FtSfN/l96jo3cwpL+BJC5spU5iXjD+4POd6a5tPtN0b4lSv9RiDkyxn/KS4JgjvV\n"
        . "sjeJFkQfDyoMcEZZ6kuyBT/uYVekYnOMA8WJDOSoD07eKHOau6PcMk3AkXPG+rA4\n"
        . "iD2jaExa0zVXZ+Fz2sVicFtrPFkUHTdbNULv9do+zwmSV24N8kfXtW76Mfh//bVW\n"
        . "aMe4flfAMw8TtYC/p+bHsWRH+hw8rPW9Z7tf1mMDBvFiZOfE8LueDug+lN0WnszJ\n"
        . "fb5xQKB/EUTKNhhiR9c4Zun8wvTZBnFmVwjvv4frCe9VFaEpZeDSMfjqkemqvNCn\n"
        . "WCx7X7Mp0Fmb0NMHG8GrkQib0jRZoVXf/x6d5sFsH8lXr18f0sBzQn20F9ae4ijN\n"
        . "hSWofg2hu6xvv5CQ6pgV/A==\n"
        . '-----END ENCRYPTED PRIVATE KEY-----';

    public function generateToken()
    {
        try {
            $clientId = 'DIGIBIMA';
            $password = 'DIGIBIMA@1234';

            // Prepare login data
            $data = json_encode([
                'client' => $clientId,
                'token_type' => 'SB'
            ]);

            // Encrypt password
            $token = $this->rsaEncrypt($password, $this->publicKey);

            // Make login request
            $curl = curl_init('https://ix-uat.careinsurance.com/api/auth/login');

            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ];

            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_error($curl)) {
                throw new \Exception('Curl error: ' . curl_error($curl));
            }

            curl_close($curl);

            return json_decode($response, true);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function uploadDocument()
    {
        try {
            $clientId = $this->clientId;
            $password = $this->password;
            $publicKey = $this->publicKey;
            $privateKey = $this->privateKey;
            $passphrase = $this->passphrase;

            $loginPayload = json_encode([
                'client' => $clientId,
                'token_type' => 'SB'
            ], JSON_UNESCAPED_SLASHES);

            $loginPayload = str_replace(["\n", "\r", ' '], '', $loginPayload);

            $rsa = \phpseclib3\Crypt\RSA::loadPublicKey($publicKey);

            $encryptedPassword = $rsa
                ->withPadding(\phpseclib3\Crypt\RSA::ENCRYPTION_OAEP)
                ->withHash('sha256')
                ->withMGFHash('sha256')
                ->encrypt($password);

            $encryptedPassword = str_replace(["\n", "\r"], '', base64_encode($encryptedPassword));

            $loginHeaders = [
                'Content-Type: application/json',
                "Authorization: Bearer $encryptedPassword"
            ];

            $ch = curl_init('https://ix-uat.careinsurance.com/api/auth/login');

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $loginPayload,
                CURLOPT_HTTPHEADER => $loginHeaders,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);

            $loginResponse = curl_exec($ch);
            curl_close($ch);

            $loginData = json_decode($loginResponse, true);

            if (!isset($loginData['response']['token'])) {
                return $loginData;
            }

            $jwt = $loginData['response']['token'];

            $requestData = [
                'client_id' => $clientId,
                'documentmetadata' => [
                    [
                        'bucket_category' => 'kyc',
                        'mimetype' => 'image/png',
                        'document_category' => 'OVD_KYC',
                        'sub_category' => 'Passport',
                        'document_name' => 'passport.png',
                        'optional_info' => new \stdClass(),
                        'customer_id' => '123456',
                        'identifier' => 'TEST123',
                        'is_encrypted' => false,
                        'is_temporary' => false
                    ]
                ],
                'allow_internal' => false
            ];

            $payload = json_encode($requestData, JSON_UNESCAPED_SLASHES);
            $payload = str_replace(["\n", "\r", ' '], '', $payload);

            $aesKey = random_bytes(32);
            $iv = random_bytes(16);

            $encryptedData = openssl_encrypt(
                $payload,
                'aes-256-cbc',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv
            );

            $rsa = \phpseclib3\Crypt\RSA::loadPublicKey($publicKey);

            $encryptedKey = $rsa
                ->withPadding(\phpseclib3\Crypt\RSA::ENCRYPTION_OAEP)
                ->withHash('sha256')
                ->withMGFHash('sha256')
                ->encrypt($aesKey);

            $encryptedKey = str_replace(["\n", "\r"], '', base64_encode($encryptedKey));

            $finalEncryptedPayload =
                bin2hex($iv) . ':'
                . bin2hex($encryptedData) . ':'
                . $encryptedKey;

            $finalPayload = json_encode([
                'request_payload' => $finalEncryptedPayload
            ], JSON_UNESCAPED_SLASHES);

            $finalPayload = str_replace(["\n", "\r"], '', $finalPayload);

            $xRequestId = hash_hmac('sha256', $finalPayload, $password);

            $headers = [
                "Authorization: Bearer $jwt",
                "x-request-id: $xRequestId",
                'Content-Type: application/json',
                'Accept: application/json'
            ];

            $ch = curl_init('https://ix-uat.careinsurance.com/api/docservice/v1/upload');

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $finalPayload,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $responseData = json_decode($response, true);

            if (isset($responseData['response']) && is_string($responseData['response'])) {
                list($ivHex, $dataHex, $keyB64) = explode(':', $responseData['response']);

                $iv = hex2bin($ivHex);
                $data = hex2bin($dataHex);
                $encKey = base64_decode($keyB64);

                $rsa = \phpseclib3\Crypt\RSA::loadPrivateKey($privateKey, $passphrase);

                $aesKey = $rsa
                    ->withPadding(\phpseclib3\Crypt\RSA::ENCRYPTION_OAEP)
                    ->withHash('sha256')
                    ->withMGFHash('sha256')
                    ->decrypt($encKey);

                $decrypted = openssl_decrypt(
                    $data,
                    'aes-256-cbc',
                    $aesKey,
                    OPENSSL_RAW_DATA,
                    $iv
                );

                $responseData['decrypted'] = $decrypted;
            }

            return $responseData;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
}

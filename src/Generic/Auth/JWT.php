<?php


namespace App\Generic\Auth;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class JWT
{
    private string $appSecret;

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack,string $appSecret)
    {
        $this->appSecret = $appSecret;
        $this->requestStack = $requestStack;
    }

    public function encode(array $data)
    {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode($data));
        $signature = hash_hmac('sha256', "$header.$payload", $this->appSecret, true);
        $signature = base64_encode($signature);

        return "$header.$payload.$signature";
    }

    public function decode(string $token)
    {
        list($header, $payload, $signature) = explode('.', $token);
        $data = json_decode(base64_decode($payload), true);
        $expectedSignature = hash_hmac('sha256', "$header.$payload", $this->appSecret, true);
        $expectedSignature = base64_encode($expectedSignature);

        if ($signature !== $expectedSignature) {
            throw new \Exception("Invalid token signature");
        }

        return $data;
    }

    public function getJWTFromHeader(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        
        if ($request instanceof Request) {
            $authorizationHeader = $request->headers->get('Authorization');

            if (strpos($authorizationHeader, 'Bearer ') === 0) {
                return substr($authorizationHeader, 7);
            }
        }
        return null;
    }
}
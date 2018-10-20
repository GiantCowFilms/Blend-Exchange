<?php declare(strict_types = 1);

namespace BlendExchange\Input;

final class IpAddress {
    private const V4 = 0;
    private const V6 = 1;
    private $type;

    private function detectType() : int {
        $v4 = '/(?:(?:2[0-5]{2}|(?:1[0-9]|[1-9])?[0-9]).){3}(2[0-5]{2}|(?:1[0-9]|[1-9])?[0-9])/m';
        if (preg_match($v4,$this->ipAddress) === 1) {
            $this->type = self::V4;
            return $this->type;
        } else {
            $this->type = self::V6;
            return $this->type;
        }
    }

    public function __construct (string $ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    public function getAnonymized () : string
    {
        $anonymized = $this->ipAddress;
        try {
            if ($this->detectType() === self::V4) {
                $anonymized = substr($anonymized,0,strrpos($anonymized,'.',-1));
            } else {
                $anonymized = substr($anonymized,0,strrpos($anonymized,':',-1));
            }
        } catch(\Exception $e) {
            
        }
        //Truncated to ensure higher probability of hash collisions.
        return substr(hash('SHA384',$anonymized),0,12);
    }
}
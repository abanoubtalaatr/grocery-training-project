<?php

namespace App\Support;

class PhoneNormalizer
{
    /**
     * Strip a duplicated country code from the start of a phone number,
     * so we never store "+201234567890" with country_code "+20" AND
     * the code baked into the phone string at the same time.
     */
    public function __invoke(string $phone, ?string $countryCode): string
    {
        $phone = trim($phone);
        $code = trim((string) $countryCode);

        if ($code !== '' && str_starts_with($phone, $code)) {
            return substr($phone, strlen($code));
        }

        return $phone;
    }
}
<?php declare(strict_types=1);

namespace tiFy\Validation\Rules;

class Email extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate($input): bool
    {
        if (!is_string($input)) {
            return false;
        } elseif (strlen($input) < 6) {
           return false;
        } elseif (strpos($input, '@', 1) === false) {
            return false;
        }

        list($local, $domain) = explode('@', $input, 2);

        $regex = "/^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~\.-]+$/";
        if (!preg_match($regex, $local)) {
            return false;
        } elseif (preg_match('/\.{2,}/', $domain)) {
            return false;
        } elseif (trim($domain, " \t\n\r\0\x0B.") !== $domain) {
            return false;
        }

        $subs = explode('.', $domain);

        if (2 > count($subs)) {
            return false;
        } else {
            foreach ($subs as $sub) {
                if (trim($sub, " \t\n\r\0\x0B-") !== $sub) {
                    return false;
                } elseif (!preg_match('/^[a-z0-9-]+$/i', $sub)) {
                    return false;
                }
            }
        }

        return true;
    }
}
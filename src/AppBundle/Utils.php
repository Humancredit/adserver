<?php
namespace AppBundle;

class Utils
{
    /**
     *
     */
    public function slugify($string)
    {
        return preg_replace('/[^a-z0-9]/', '-', strtolower(trim(strip_tags($string))));
    }

    /**
     *
     */
    public function generateSignedUrl($banner, $webroot, $key)
    {
        $url = $webroot.$banner->getWebPath();
        $url .= "?hc_c=".$banner->getCategory()->getSlug();
        $url .= "&hc_b=".$banner->getBrand()->getSlug();
        $pkeyid = openssl_pkey_get_private($key);
        openssl_sign($url, $signature, $pkeyid, "sha256WithRSAEncryption");
        openssl_free_key($pkeyid);
        return $url."&hc_s=".bin2hex($signature);
    }

}

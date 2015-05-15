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
    public function generateSignedUrl($url, $key)
    {
        $pkeyid = openssl_pkey_get_private($key);
        openssl_sign($url, $signature, $pkeyid, "sha256WithRSAEncryption");
        openssl_free_key($pkeyid);
        return $url.(strpos($url, "?") !== false ? "&" : "?")."hc_ad_sg=".bin2hex($signature);
    }

    /**
     *
     */
    public function generateEmbedCode($url, $key)
    {
        $url = $this->generateSignedUrl($url, $key);
        return '<iframe class="humancredit" seamless="seamless" frameborder="0" style="width:728px;height:90px" scrolling="no" src="'.$url.'"></iframe>';
    }

}

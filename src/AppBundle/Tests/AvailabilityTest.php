<?php

namespace AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvailabilityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        echo "SUCCESS? ".$client->getResponse()->isSuccessful();
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function urlProvider()
    {
        return array( array('/'),
            //array('/demo/'),
            //array('/demo/contact'),
            // ...
        );
    }

}

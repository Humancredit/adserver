<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Banner;
use AppBundle\Entity\BannerLog;

/**
 * Banner controller.
 *
 * @Route("/banner")
 */
class BannerController extends Controller
{

    /**
     * Lists all Banner entities.
     *
     * @Route("/", name="banner")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Banner')->findAll();
        return array('entities' => $entities, );
    }

    /**
     * Finds and displays a Banner entity.
     *
     * @Route("/{id}", name="banner_show")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Banner')->find($id);
        $webroot = $this->get('request')->getBasePath().'/';

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $log = new BannerLog();
        $log->setBanner($entity);
        $em->persist($log);
        $em->flush();

        return array(
            'entity' => $entity,
            'signedUrl' => $this->generateSignedUrl($entity, $webroot)
        );
    }

    /**
     *
     */
    private function generateSignedUrl($entity, $webroot)
    {
        header("Content-Type: application/json");

        $url = $webroot.$entity->getWebPath();
        $url .= "?hc_c=".$entity->getCategory()->getSlug();
        $url .= "&hc_b=".$entity->getBrand()->getSlug();
        $pkeyid = openssl_pkey_get_private("file://".$this->get('kernel')->getRootDir().'/data/private.key');
        openssl_sign($url, $signature, $pkeyid, "sha256WithRSAEncryption");
        openssl_free_key($pkeyid);
        return $url."&hc_s=".bin2hex($signature);
    }

}

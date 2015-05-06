<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Brand;
use AppBundle\Entity\BannerLog;

/**
 * Brand controller.
 *
 * @Route("/brand")
 */
class BrandController extends Controller
{

    /**
     * Lists all Brand entities.
     *
     * @Route("/", name="brand")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Brand')->findAll();
        return array('entities' => $entities, );
    }

    /**
     * Finds and displays a Brand entity.
     *
     * @Route("/{id}", name="brand_show")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Brand')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        $data = array('entity' => $entity);
        $bannerCount = $entity->getBanners()->count();
        if ($bannerCount) {
            $webroot = $this->get('request')->getBasePath().'/';
            $key = "file://".$this->get('kernel')->getRootDir().'/data/private.key';
            $data['banner'] = $entity->getBanners()->get(rand(0, $bannerCount - 1));
            $data['signedUrl'] = $this->get('app.utils')->generateSignedUrl($data['banner'], $webroot, $key);

            $log = new BannerLog();
            $log->setBanner($data['banner']);
            $em->persist($log);
            $em->flush();
        }

        return $data;
    }

}

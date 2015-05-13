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
            // embed code
            $key = "file://".$this->get('kernel')->getRootDir().'/data/private.key';
            $url = $this->get('router')->generate('category_embed', array('id' => $entity->getId()), true);
            $url .= '?br='.$entity->getSlug();
            $data['embedCode'] = $this->get('app.utils')->generateEmbedCode($url, $key);
        }

        return $data;
    }

    /**
     * Embed a brand entity
     *
     * @Route("/{id}/embed", name="brand_embed")
     * @Method("GET")
     * @Template()
     */
    public function embedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Brand')->find($id);
        $webroot = $this->get('request')->getBasePath().'/';

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        $bC = $this->get('app.banner_controller');
        $bC->setContainer($this->container);
        $bannerCount = $entity->getBanners()->count();
        if ($bannerCount) {
            $banner = $entity->getBanners()->get(rand(0, $bannerCount - 1));
            $banner = $bC->prepareBanner($banner);
        }

        if ($banner) {
            return array_merge(array('entity' => $entity), $banner);
        }
        return array('entity' => $entity);
    }

}

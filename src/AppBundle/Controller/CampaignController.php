<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\BannerLog;

/**
 * Campaign controller.
 *
 * @Route("/campaign")
 */
class CampaignController extends Controller
{

    /**
     * Lists all Campaign entities.
     *
     * @Route("/", name="campaign")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Campaign')->findAll();

        return array('entities' => $entities, );
    }

    /**
     * Finds and displays a Campaign entity.
     *
     * @Route("/{id}", name="campaign_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Campaign')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        $data = array('entity' => $entity);
        $bannerCount = $entity->getBanners()->count();
        if ($bannerCount) {
            // embed code
            $key = "file://".$this->get('kernel')->getRootDir().'/data/private.key';
            $url = $this->get('router')->generate('campaign_embed', array('id' => $entity->getId()), true);
            $url .= '?cp='.$entity->getSlug();
            $data['embedCode'] = $this->get('app.utils')->generateEmbedCode($url, $key);
        }

        return $data;
    }

    /**
     * Embed a campaign entity
     *
     * @Route("/{id}/embed", name="campaign_embed")
     * @Method("GET")
     * @Template()
     */
    public function embedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Campaign')->find($id);
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

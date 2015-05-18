<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Category;
use AppBundle\Entity\BannerLog;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Category')->findAll();

        return array('entities' => $entities, );
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Category')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $data = array('entity' => $entity);
        $bannerCount = $entity->getBanners()->count();
        if ($bannerCount) {
            // embed code
            $key = "file://".$this->get('kernel')->getRootDir().'/data/private.key';
            $url = $this->get('router')->generate('category_embed', array('id' => $entity->getId()), true);
            $url .= '?ct='.$entity->getSlug();
            $data['embedCode'] = $this->get('app.utils')->generateEmbedCode($url, $entity->getWidth(), $entity->getHeight(), $key);
        }

        return $data;
    }

    /**
     * Embed a category entity
     *
     * @Route("/{id}/embed", name="category_embed")
     * @Method("GET")
     * @Template()
     */
    public function embedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Category')->find($id);
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

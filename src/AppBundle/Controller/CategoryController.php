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

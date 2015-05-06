<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\BannerLog;

/**
 * BannerLog controller.
 *
 * @Route("/banner")
 */
class BannerLogController extends Controller
{

    /**
     * Lists all BannerLog entities.
     *
     * @Route("/", name="banner_log")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:BannerLog')->findAll();
        return array('entities' => $entities, );
    }

    /**
     * Finds and displays a BannerLog entity.
     *
     * @Route("/{id}", name="banner_log_show")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:BannerLog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BannerLog entity.');
        }

        return array('entity' => $entity);
    }

}

<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Banner;

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
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Banner')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Banner entity.
     *
     * @Route("/{id}", name="banner_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}

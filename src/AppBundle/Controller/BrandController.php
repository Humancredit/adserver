<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Brand;

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
        return array('entity' => $entity, );
    }

}

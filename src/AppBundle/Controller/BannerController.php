<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Banner;
use AppBundle\Entity\BannerClick;
use AppBundle\Entity\BannerFeedback;
use AppBundle\Entity\BannerLog;
use AppBundle\Form\BannerFeedbackType;

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

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        // :TODO: key is user specific
        $key = "file://".$this->get('kernel')->getRootDir().'/data/private.key';
        $webroot = $this->get('request')->getBasePath().'/';

        // feedback form
        $feedback = new BannerFeedback();
        $feedback->setBanner($entity);
        $url = $this->get('router')->generate('banner_feedback', array('id' => $entity->getId()), true);
        $url = $this->get('app.utils')->generateSignedUrl($url, $key);
        $form = $this->createForm(new BannerFeedbackType(), $feedback, array('action' => $url));

        // embed code
        $url = $this->get('router')->generate('banner_embed', array('id' => $entity->getId()), true);
        $url .= "?c=".$entity->getCategory()->getSlug();
        $url .= "&b=".$entity->getBrand()->getSlug();
        $embedCode = $this->get('app.utils')->generateEmbedCode($url, $key);

        // put everything together
        return array(
            'entity' => $entity,
            'embedCode' => $embedCode,
            'feedbackForm' => $form->createView()
        );
    }

    /**
     * Embed a banner entity
     *
     * @Route("/{id}/embed", name="banner_embed")
     * @Method("GET")
     * @Template()
     */
    public function embedAction($id)
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

        // :TODO: key is user specific
        $key = "file://".$this->get('kernel')->getRootDir().'/data/private.key';

        // image url
        $url = $webroot.$entity->getWebPath();
        $url .= "?c=".$entity->getCategory()->getSlug();
        $url .= "&b=".$entity->getBrand()->getSlug();
        $signedImage = $this->get('app.utils')->generateSignedUrl($url, $key);

        // link url
        $url = $this->get('router')->generate('banner_click', array('id' => $entity->getId()), true);
        $url .= "?c=".$entity->getCategory()->getSlug();
        $url .= "&b=".$entity->getBrand()->getSlug();
        $signedLink = $this->get('app.utils')->generateSignedUrl($url, $key);

        // feedback url
        $url = $this->get('router')->generate('banner_feedback', array('id' => $entity->getId()), true);
        $url .= "?c=".$entity->getCategory()->getSlug();
        $url .= "&b=".$entity->getBrand()->getSlug();
        $signedFeedback = $this->get('app.utils')->generateSignedUrl($url, $key);

        return array(
            'entity' => $entity,
            'signedLink' => $signedLink,
            'signedFeedback' => $signedFeedback,
            'signedImage' => $signedImage
        );
    }

    /**
     * Click on a Banner. Count click and redirect to target url.
     *
     * @Route("/{id}/click", name="banner_click")
     * @Method("GET")
     * @Template()
     */
    public function clickAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $click = new BannerClick();
        $click->setBanner($entity);
        $em->persist($click);
        $em->flush();

        return $this->redirect($entity->getUrl());
    }

    /**
     * Give Feedback for a banner
     *
     * @Route("/{id}/feedback", name="banner_feedback")
     * @Method("POST")
     */
    public function feedbackAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Banner')->find($id);
        $webroot = $this->get('request')->getBasePath().'/';

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $form = $this->createForm(new BannerFeedbackType());
        $form->handleRequest($request);
        $response = new \stdClass();
        $response->result = 'error';

        if ($form->isValid()) {
            $feedback = $form->getData();
            $feedback->setBanner($entity);
            $em->persist($feedback);
            $em->flush();
            $response->result = 'ok';
        }

        $response = new Response(json_encode($response));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}

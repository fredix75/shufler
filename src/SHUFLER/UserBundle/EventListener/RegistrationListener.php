<?php
namespace SHUFLER\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use FOS\UserBundle\Form\Type\RegistrationFormType;

/**
 * Ajax listener on FOS UserBundle registration
 */
class RegistrationListener implements EventSubscriberInterface
{

    private $router;

    public function __construct(RequestStack $RequestStack)
    {
        $this->requestStack = $RequestStack;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_FAILURE => 'onRegistrationFailure'
        );
    }

    /**
     * onRegistrationSuccess
     *
     * @param FormEvent $event            
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        
        if ($request->isXmlHttpRequest()) {
            $success = true;
        } else {
            $success = false;
        }
        $response = new Response(json_encode(array(
            'success' => $success
        )));
        
        $response->headers->set('Content-Type', 'application/json');
        $event->setResponse($response);
    }
    /**
     * onRegistrationFailure
     *
     *  == Marche pas ==
     * 
     * @param FormEvent $event
     */
    public function onRegistrationFailure(FormEvent $event)
    {                
        $response = new Response(json_encode(array(
            'success' => false,
        )));

        $event->setResponse($response);
    }
}
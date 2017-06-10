<?php
namespace SHUFLER\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        // Prepare your response
        if ($request->isXmlHttpRequest()) {
            $array = array( 'success' => true ); // data to return via JSON
            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );
        } else {
            $array = array( 'success' => false ); // data to return via JSON
            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );
        }

        // Send it
        $event->setResponse($response);
    }
}
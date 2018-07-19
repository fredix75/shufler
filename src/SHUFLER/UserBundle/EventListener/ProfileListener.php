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
class ProfileListener implements EventSubscriberInterface
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
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileSuccess'
        );
    }

    /**
     * onProfileSuccess
     *
     * @param FormEvent $event            
     */
    public function onProfileSuccess(FormEvent $event)
    {
        $response = new Response(json_encode(array(
            'success' => true
        )));
        
        $response->headers->set('Content-Type', 'application/json');
        $event->setResponse($response);
    }
}
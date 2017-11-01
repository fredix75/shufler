<?php
namespace SHUFLER\UserBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{

    private $router;

    private $session;

    /**
     * Constructor
     *
     * @author Joe Sexton
     * @param RouterInterface $router            
     * @param Session $session            
     */
    public function __construct(RouterInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * onAuthenticationSuccess
     *
     * @author Joe Sexton
     * @param Request $request            
     * @param TokenInterface $token            
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // if AJAX login
        if ($request->isXmlHttpRequest()) {
            
            $response = new Response(json_encode(array(
                'success' => true
            )));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
            
            // if form login
        } else {
            
            if ($this->session->get('_security.main.target_path')) {
                
                $url = $this->session->get('_security.main.target_path');
            } else {
                
                $url = $this->router->generate('shufler_homepage');
            } // end if
            return new RedirectResponse($url);
        }
    }

    /**
     * onAuthenticationFailure
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface::onAuthenticationFailure()
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // if AJAX login
             
            $response = new Response(json_encode(array(
                'success' => false,
                'message' => $exception->getMessage()
            )));
           // $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        
    }
}
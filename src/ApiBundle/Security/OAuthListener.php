<?php

namespace ApiBundle\Security;

use FOS\OAuthServerBundle\Security\Firewall\OAuthListener as ListenerBase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * OAuthListener
 *
 * @author rogerio
 */
class OAuthListener implements ListenerInterface
{
    /**
     */
    private $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function handle(GetResponseEvent $event)
    {
        $manager = $this->container->get('security.authentication.manager');
        $storate = $this->container->get('security.token_storage');
        $server  = $this->container->get('fos_oauth_server.server');
        
        $session = $event->getRequest()->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }
        
        $serializedToken = $session->get('_security_main');
        $token = unserialize($serializedToken);
        
        if ($token instanceof TokenInterface) {
            $storate->setToken($token);
        }
        
        $wrapped = new ListenerBase($storate, $manager, $server);
        
        return $wrapped->handle($event);
    }
}
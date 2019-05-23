<?php

namespace App\Twig;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class ApiBootstrapExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * ApiBootstrapExtension constructor.
     *
     * @param Security                 $security
     * @param RouterInterface          $router
     * @param JWTTokenManagerInterface $tokenManager
     */
    public function __construct(Security $security, RouterInterface $router, JWTTokenManagerInterface $tokenManager)
    {
        $this->security = $security;
        $this->router = $router;
        $this->tokenManager = $tokenManager;
    }

    public function getGlobals()
    {
        $user = $this->security->getUser();
        if (null !== $user) {
            $token = $this->tokenManager->create($user);
        }

        return [
            'entrypoint' => $this->router->generate('api_entrypoint'),
            'jwt' => $token ?? null,
        ];
    }
}

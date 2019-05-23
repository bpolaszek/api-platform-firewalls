<?php

namespace App\Controller\Customer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class HomeController
{
    /**
     * @Route("/", name="customer_home")
     * @Template("home.html.twig")
     */
    public function __invoke(UserInterface $user)
    {
        return [
            'title' => 'Customer home',
        ];
    }
}

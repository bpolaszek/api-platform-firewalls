<?php

namespace App\Controller\Employee;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class HomeController
{
    /**
     * @Route("/", name="employee_home")
     * @Template("home.html.twig")
     */
    public function __invoke(UserInterface $user)
    {
        return [
            'title' => 'Employee home',
        ];
    }

}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    const SECTIONS = ['Home','About','Services', 'Contact'];

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $keys= ['<=12.000', '12.000<=24.000','24.0000<=36.000', '>=36.000'];
        $values=[
            'es' => ['0 - USD $12.000', 'USD $12.000 - USD $24.000', 'USD $24.000 - USD $36.000', 'Más de USD $36.000'],
            'en' => ['0 - USD $12,000', 'USD $12,000 - USD $24,000', 'USD $24,000 - USD $36,000', 'Over USD $36,000'],
        ];
        $budget = array_combine($keys, $values[$request->getLocale()]);
        return $this->render('home/index.html.twig', compact('budget'));
    }
}

<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use App\Service\ConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private ConfigService $configService,
        private ServiceRepository $serviceRepository
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $services = $this->serviceRepository->findActiveServices();
        
        return $this->render('home/index.html.twig', [
            'services' => $services,
            'config' => $this->configService,
        ]);
    }

    #[Route('/services', name: 'services')]
    public function services(): Response
    {
        $services = $this->serviceRepository->findActiveServices();
        
        return $this->render('home/services.html.twig', [
            'services' => $services,
            'config' => $this->configService,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'config' => $this->configService,
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'config' => $this->configService,
        ]);
    }
}

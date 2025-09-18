<?php

namespace App\Controller\Api;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    public function __construct(
        private ServiceRepository $serviceRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/services', name: 'services', methods: ['GET'])]
    public function getServices(): JsonResponse
    {
        $services = $this->serviceRepository->findActiveServices();
        
        $data = [];
        foreach ($services as $service) {
            $data[] = [
                'id' => $service->getId(),
                'slug' => $service->getSlug(),
                'title' => $service->getTitle() ?? 'Service Title',
                'description' => $service->getDescription() ?? 'Service description...',
                'price' => $service->getPrice() ?? 2500,
                'isActive' => $service->isActive(),
            ];
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'count' => count($data)
        ]);
    }

    #[Route('/quote', name: 'quote', methods: ['POST'])]
    public function requestQuote(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Validate required fields
        $requiredFields = ['name', 'email', 'service', 'package'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json([
                    'success' => false,
                    'message' => "Field '{$field}' is required"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // Basic email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid email format'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Calculate price
        $basePrice = $this->getServiceBasePrice($data['service']);
        $multiplier = $this->getPackageMultiplier($data['package']);
        $totalPrice = $basePrice * $multiplier;

        // Here you would typically save to database and send email
        // For now, we'll just return a success response
        
        return $this->json([
            'success' => true,
            'message' => 'Quote request received successfully',
            'data' => [
                'quote_id' => 'Q' . time(),
                'estimated_price' => $totalPrice,
                'service' => $data['service'],
                'package' => $data['package'],
                'contact_within' => '24 hours'
            ]
        ]);
    }

    #[Route('/contact', name: 'contact', methods: ['POST'])]
    public function contactForm(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Validate required fields
        $requiredFields = ['name', 'email', 'message'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json([
                    'success' => false,
                    'message' => "Field '{$field}' is required"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // Basic email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid email format'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Here you would typically save to database and send email
        // For now, we'll just return a success response
        
        return $this->json([
            'success' => true,
            'message' => 'Message sent successfully. We will contact you soon!',
            'data' => [
                'ticket_id' => 'T' . time(),
                'response_time' => '24 hours'
            ]
        ]);
    }

    #[Route('/newsletter', name: 'newsletter', methods: ['POST'])]
    public function newsletter(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (empty($data['email'])) {
            return $this->json([
                'success' => false,
                'message' => 'Email is required'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid email format'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Here you would typically save to newsletter database
        
        return $this->json([
            'success' => true,
            'message' => 'Successfully subscribed to newsletter!'
        ]);
    }

    #[Route('/price-calculator', name: 'price_calculator', methods: ['POST'])]
    public function priceCalculator(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (empty($data['service']) || empty($data['package'])) {
            return $this->json([
                'success' => false,
                'message' => 'Service and package are required'
            ], Response::HTTP_BAD_REQUEST);
        }

        $basePrice = $this->getServiceBasePrice($data['service']);
        $multiplier = $this->getPackageMultiplier($data['package']);
        $totalPrice = $basePrice * $multiplier;

        return $this->json([
            'success' => true,
            'data' => [
                'service' => $data['service'],
                'package' => $data['package'],
                'base_price' => $basePrice,
                'multiplier' => $multiplier,
                'total_price' => $totalPrice,
                'currency' => 'EUR'
            ]
        ]);
    }

    #[Route('/availability', name: 'availability', methods: ['GET'])]
    public function getAvailability(Request $request): JsonResponse
    {
        // Mock availability data - in real implementation, this would come from a calendar system
        $availableDates = [];
        $today = new \DateTime();
        
        for ($i = 7; $i <= 30; $i++) {
            $date = clone $today;
            $date->add(new \DateInterval("P{$i}D"));
            
            // Skip weekends for this example
            if ($date->format('N') < 6) {
                $availableDates[] = [
                    'date' => $date->format('Y-m-d'),
                    'time_slots' => ['09:00', '11:00', '14:00', '16:00']
                ];
            }
        }

        return $this->json([
            'success' => true,
            'data' => $availableDates
        ]);
    }

    private function getServiceBasePrice(string $service): int
    {
        $prices = [
            'hair-transplant' => 2500,
            'plastic-surgery' => 3000,
            'dental-care' => 1500,
            'aesthetic-treatments' => 1000
        ];

        return $prices[$service] ?? 2000;
    }

    private function getPackageMultiplier(string $package): float
    {
        $multipliers = [
            'basic' => 1.0,
            'premium' => 1.5,
            'vip' => 2.0
        ];

        return $multipliers[$package] ?? 1.0;
    }
}
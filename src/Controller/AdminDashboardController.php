<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $stats)
    {


        $bestAds = $stats->getAdsStats('DESC');

        $worstAds = $stats->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            // 'stats'    => compact('users', 'ads', 'bookings', 'comments'),
            'stats'   => $stats->getStats(),
            'bestAds'  => $bestAds,
            'worstAds' => $worstAds
            // 'stats' => [
            //     'users'    => $users,
            //     'ads'      => $ads,
            //     'bookings' => $bookings,
            //     'comments' => $comments
            // ]
        ]);
    }
}

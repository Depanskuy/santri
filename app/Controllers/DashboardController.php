<?php

namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;

class DashboardController
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $antrianModel = new Antrian();
        $dokterModel = new Dokter();

        $data = [
            'title' => 'Dashboard Utama',
            'user' => $_SESSION['user'],
            'total_antrian' => method_exists($antrianModel, 'countAll') ? $antrianModel->countAll() : 0,
            'total_dokter' => method_exists($dokterModel, 'countAll') ? $dokterModel->countAll() : 0
        ];

        return View::render('dashboard', $data, 'main');
    }
}

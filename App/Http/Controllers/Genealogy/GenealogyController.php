<?php
namespace App\Http\Controllers\Genealogy;

use App\Core\BaseController;
use App\Services\Genealogy\GenealogyService;

class GenealogyController extends BaseController {
    private $genealogy_service;

    public function __construct() {
        $this->genealogy_service = new GenealogyService();
    }

    // Renders the main view page
    public function showGenealogyPage() {
        if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] === true) {
            $this->renderView("User/genealogy");
        } else {
            header("Location: /login");
            exit;
        }
    }

    // API Endpoint for the JavaScript Tree
     public function getTreeData() {
        // Clear any accidental output (like the "Route: " string)
        if (ob_get_length()) ob_clean();

        header('Content-Type: application/json');
        
        $userId = $_SESSION["user_id"] ?? null;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $data = $this->genealogy_service->getFormattedTree($userId);
        echo json_encode($data);
        
        // Terminate immediately so WP doesn't add HTML
        exit;
    }

    public function getStats() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        $userId = $_SESSION["user_id"] ?? null;
        $data = $this->genealogy_service->getUserStats($userId);
        echo json_encode($data);
        exit;
    }

    public function getUplineData() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        $userId = $_SESSION["user_id"] ?? null;
        $data = $this->genealogy_service->getUpline($userId);
        echo json_encode($data);
        exit;
    }
}
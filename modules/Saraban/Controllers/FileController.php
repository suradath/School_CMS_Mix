<?php
declare(strict_types=1);

namespace Modules\Saraban\Controllers;

use Core\Controller;
use Modules\Saraban\Models\SarabanDocument;

class FileController extends Controller
{
    public function serve(int $id): void
    {
        $this->requireAuth();
        $doc = SarabanDocument::find($id);
        
        if (!$doc || empty($doc['file_url'])) {
            header("HTTP/1.0 404 Not Found");
            die("File not found");
        }

        $filePath = ROOT_PATH . $doc['file_url'];
        
        if (!file_exists($filePath)) {
            header("HTTP/1.0 404 Not Found");
            die("File does not exist on server");
        }

        $mimeType = mime_content_type($filePath);
        header("Content-Type: " . $mimeType);
        header("Content-Length: " . filesize($filePath));
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        readfile($filePath);
        exit;
    }
}

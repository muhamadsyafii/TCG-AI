<?php
include 'koneksi.php';
require_once 'dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['project']) && isset($_GET['format'])) {
    $project = $_GET['project'];
    $format = $_GET['format'];
    
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    // Filter Query berdasarkan Project dan Format
    $query = "SELECT * FROM test_cases WHERE 1=1";
    if ($project !== 'ALL_PROJECTS') { $query .= " AND project_name = '" . $conn->real_escape_string($project) . "'"; }
    if ($format !== 'ALL_FORMATS') { $query .= " AND tc_format = '" . $conn->real_escape_string($format) . "'"; }
    $query .= " ORDER BY project_name, id ASC";
    
    $res = $conn->query($query);

    $html = '
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #0194f3; padding-bottom: 10px; margin-bottom: 20px; }
        .tc-card { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-left: 5px solid #10b981; border-radius: 5px;}
        .tc-card.negative { border-left: 5px solid #ef4444; }
        .badge { font-weight: bold; font-size: 10px; padding: 3px 6px; background: #eee; border-radius: 3px; margin-right: 5px; }
        .badge-blue { background: #0194f3; color: white; }
        .box-info { background: #f8f9fa; padding: 8px; font-size: 11px; border: 1px solid #ddd; margin-bottom: 10px; }
        .steps { background: #f9f9f9; padding: 10px; font-family: monospace; font-size: 12px; margin: 10px 0; border: 1px solid #eee; }
        .expected { font-size: 12px; color: #444; font-style: italic; }
    </style>
    <div class="header">
        <h1>Dokumen Test Case QA</h1>
        <p>Project: <strong>' . ($project == 'ALL_PROJECTS' ? 'Semua Project' : htmlspecialchars($project)) . '</strong> | Format: <strong>' . ($format == 'ALL_FORMATS' ? 'Semua Format' : htmlspecialchars($format)) . '</strong></p>
    </div>';

    while($row = $res->fetch_assoc()) {
        $class = ($row['test_type'] == 'NEGATIVE') ? 'negative' : '';
        $tcNumber = "TC-" . str_pad($row['id'], 4, "0", STR_PAD_LEFT);
        $tcFormat = isset($row['tc_format']) ? $row['tc_format'] : 'STANDARD';

        $html .= '
        <div class="tc-card ' . $class . '">
            <div style="margin-bottom: 10px;">
                <span class="badge">' . $tcNumber . '</span>
                <span class="badge">' . $row['test_type'] . '</span>
                <span class="badge badge-blue">' . $tcFormat . '</span>
            </div>
            <h3 style="margin: 0 0 10px 0; font-size: 16px;">' . htmlspecialchars($row['title']) . '</h3>
            
            <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; padding-right: 5px;">
                        <div class="box-info"><strong>Precondition:</strong><br/>' . htmlspecialchars($row['precondition'] ?? '-') . '</div>
                    </td>
                    <td style="width: 50%; padding-left: 5px;">
                        <div class="box-info"><strong>Data Test:</strong><br/>' . htmlspecialchars($row['data_test'] ?? '-') . '</div>
                    </td>
                </tr>
            </table>

            <div class="steps">' . nl2br(htmlspecialchars($row['steps'])) . '</div>
            <div class="expected"><strong>Expected Result:</strong> ' . htmlspecialchars($row['expected_result']) . '</div>
        </div>';
    }

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("TestCase_" . $project . ".pdf", array("Attachment" => 1));
}
?>
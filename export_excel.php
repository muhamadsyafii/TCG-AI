<?php
include 'koneksi.php';

if (isset($_GET['project']) && isset($_GET['format'])) {
    $project = $_GET['project'];
    $format = $_GET['format'];

    // Nama file dinamis (misal: TestCase_Login.xls)
    $filename = "TestCase_" . ($project == 'ALL_PROJECTS' ? 'Semua_Project' : str_replace(' ', '_', $project)) . ".xls";

    // Perintah sakti untuk memaksa browser mendownloadnya sebagai file Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Filter Query
    $query = "SELECT * FROM test_cases WHERE 1=1";
    if ($project !== 'ALL_PROJECTS') { $query .= " AND project_name = '" . $conn->real_escape_string($project) . "'"; }
    if ($format !== 'ALL_FORMATS') { $query .= " AND tc_format = '" . $conn->real_escape_string($format) . "'"; }
    $query .= " ORDER BY project_name, id ASC";
    
    $res = $conn->query($query);
    ?>
    
    <table border="1">
        <thead>
            <tr>
                <th style="background-color: #f3f4f6; font-weight: bold;">No TC</th>
                <th style="background-color: #f3f4f6; font-weight: bold;">Format</th>
                <th style="background-color: #f3f4f6; font-weight: bold;">Fitur / Project</th>
                <th style="background-color: #f3f4f6; font-weight: bold;">Scenario</th>
                <th style="background-color: #f3f4f6; font-weight: bold;">Precondition</th>
                <th style="background-color: #f3f4f6; font-weight: bold;">Data Test</th>
                <th style="background-color: #f3f4f6; font-weight: bold;">Test Step</th>
                <th style="background-color: #f3f4f6; font-weight: bold;">Expectation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while($row = $res->fetch_assoc()) {
                $tcNumber = "TC-" . str_pad($row['id'], 4, "0", STR_PAD_LEFT);
                $tcFormat = isset($row['tc_format']) ? $row['tc_format'] : 'STANDARD';
                
                // Trik khusus agar baris baru (Enter) tetap rapi dalam 1 kotak cell Excel
                $steps = str_replace("\n", "<br style='mso-data-placement:same-cell;' />", htmlspecialchars($row['steps']));
                $expected = str_replace("\n", "<br style='mso-data-placement:same-cell;' />", htmlspecialchars($row['expected_result']));
                $precondition = str_replace("\n", "<br style='mso-data-placement:same-cell;' />", htmlspecialchars($row['precondition'] ?? '-'));
                $dataTest = str_replace("\n", "<br style='mso-data-placement:same-cell;' />", htmlspecialchars($row['data_test'] ?? '-'));

                echo "<tr>";
                echo "<td>{$tcNumber}</td>";
                echo "<td>{$tcFormat}</td>";
                echo "<td>" . htmlspecialchars($row['project_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>{$precondition}</td>";
                echo "<td>{$dataTest}</td>";
                echo "<td>{$steps}</td>";
                echo "<td>{$expected}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>
<?php
include 'koneksi.php';

$apiKey = 'AIzaSyBYe8hJXv13H978TeSorCukgY-pXRf8rKc'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectName = $_POST['project_name'];
    $requirement = $_POST['requirement'];
    $tcFormat    = $_POST['tc_format'];

    $formatInstruction = "";
    if ($tcFormat == 'BDD') {
        $formatInstruction = "Gunakan format BDD / Gherkin (Given, When, And, Then) pada bagian steps.";
    } elseif ($tcFormat == 'ACTION_EXPECTED') {
        $formatInstruction = "Gunakan format Action & Expected per langkah. Contoh:\n1. [Action] Klik login -> [Expected] Form muncul";
    } else {
        $formatInstruction = "Gunakan format langkah standar (1, 2, 3) pada bagian steps.";
    }

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;
    
    // Prompt dipertegas: Minta string, bukan array
    $prompt = "Anda adalah Senior QA Tester. Buatkan Test Case Positif dan Negatif berdasarkan Requirement berikut:\n\n" . 
              $requirement . "\n\n" .
              $formatInstruction . "\n\n" .
              "WAJIB JAWAB HANYA DALAM FORMAT JSON BERIKUT TANPA TEKS TAMBAHAN. PASTIKAN 'steps' ADALAH SATU STRING TEKS UTUH (BUKAN ARRAY):\n" .
              '[{"type": "POSITIVE", "title": "Nama Skenario", "precondition": "Kondisi awal (isi strip - jika tidak ada)", "data_test": "Data inputan (isi strip - jika tidak ada)", "steps": "Langkah-langkah dalam satu string teks...", "expected": "Hasil akhir yang diharapkan"}, {"type": "NEGATIVE", "title": "...", "precondition": "...", "data_test": "...", "steps": "...", "expected": "..."}]';

    $data = ["contents" => [["parts" => [["text" => $prompt]]]]];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $aiText = str_replace(['```json', '```'], '', $result['candidates'][0]['content']['parts'][0]['text']);
        $testCases = json_decode(trim($aiText), true);

        if (is_array($testCases)) {
            $stmt = $conn->prepare("INSERT INTO test_cases (project_name, test_type, tc_format, title, precondition, data_test, steps, expected_result) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($testCases as $tc) {
                // JARING PENGAMAN: Jika AI bandel mengirim array, gabungkan jadi teks dengan Enter (\n)
                $precondition = isset($tc['precondition']) ? (is_array($tc['precondition']) ? implode("\n", $tc['precondition']) : $tc['precondition']) : '-';
                $dataTest     = isset($tc['data_test']) ? (is_array($tc['data_test']) ? implode("\n", $tc['data_test']) : $tc['data_test']) : '-';
                $steps        = isset($tc['steps']) ? (is_array($tc['steps']) ? implode("\n", $tc['steps']) : $tc['steps']) : '-';
                $expected     = isset($tc['expected']) ? (is_array($tc['expected']) ? implode("\n", $tc['expected']) : $tc['expected']) : '-';
                
                $stmt->bind_param("ssssssss", $projectName, $tc['type'], $tcFormat, $tc['title'], $precondition, $dataTest, $steps, $expected);
                $stmt->execute();
            }
            $stmt->close();
            header("Location: index.php?status=success");
            exit();
        }
    }
    die("Error atau AI tidak membalas JSON yang valid. Coba lagi.");
}
?>
<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>TCG - Test Case Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', }
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }
    </script>
    <style>
        * { transition: background-color 0.3s, border-color 0.3s; }
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #0194f3; border-radius: 4px; }
        #loadingOverlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; flex-direction: column; align-items: center; justify-content: center; }
        .loader { border: 4px solid #e1e1e1; border-top: 4px solid #0194f3; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .excel-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .excel-table th, .excel-table td { border: 1px solid #d1d5db; padding: 12px; text-align: left; vertical-align: top; white-space: pre-line; }
        .excel-table th { background-color: #f3f4f6; font-weight: bold; color: #374151; }
        .dark .excel-table th { background-color: #1f2937; color: #e5e7eb; border-color: #374151; }
        .dark .excel-table td { border-color: #374151; color: #d1d5db; }
    </style>
</head>
<body class="p-4 md:p-8 font-sans text-sm bg-[#f7f9fa] text-[#434343] dark:bg-[#12141a] dark:text-[#a1a1aa]">

    <div id="loadingOverlay" class="bg-white/90 dark:bg-[#12141a]/95">
        <div class="loader mb-4"></div>
        <p class="text-[#0194f3] font-bold text-sm animate-pulse uppercase tracking-widest text-center">AI sedang merancang Test Case...</p>
    </div>

    <div id="exportModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[10000] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#181a20] rounded-2xl p-6 w-full max-w-md shadow-2xl border border-gray-100 dark:border-gray-800">
            <h3 class="text-gray-800 dark:text-white font-bold mb-4 flex items-center gap-2 text-lg">
                <svg class="w-6 h-6 text-[#0194f3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Laporan Test Case
            </h3>
            
            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase">Pilih Project</label>
            <select id="modalProjectSelect" class="w-full bg-gray-50 dark:bg-[#0d0f13] border border-gray-200 dark:border-gray-700 rounded-xl text-[#0194f3] text-sm p-3 mb-4 focus:outline-none font-bold">
                <option value="ALL_PROJECTS">SEMUA PROJECT</option>
                <?php
                // Query terisolasi khusus modal agar aman
                $qModal = $conn->query("SELECT DISTINCT project_name FROM test_cases ORDER BY project_name ASC");
                if($qModal){ while($p = $qModal->fetch_assoc()){ echo "<option value='".htmlspecialchars($p['project_name'])."'>".strtoupper(htmlspecialchars($p['project_name']))."</option>"; } }
                ?>
            </select>

            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase">Pilih Format Penulisan</label>
            <select id="modalFormatSelect" class="w-full bg-gray-50 dark:bg-[#0d0f13] border border-gray-200 dark:border-gray-700 rounded-xl text-[#0194f3] text-sm p-3 mb-8 focus:outline-none font-bold">
                <option value="ALL_FORMATS">SEMUA FORMAT</option>
                <option value="BDD">BDD / Gherkin</option>
                <option value="ACTION_EXPECTED">Action & Expected</option>
                <option value="STANDARD">Standard Steps</option>
            </select>

            <div class="flex gap-2 text-[11px] font-bold uppercase">
                <button onclick="closeExportModal()" class="flex-1 py-3 rounded-xl bg-gray-100 dark:bg-[#1e232d] text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">Batal</button>
                <button onclick="confirmDownload('pdf')" class="flex-1 py-3 rounded-xl bg-[#ef4444] text-white hover:bg-[#dc2626] transition-all shadow-lg shadow-red-500/20">PDF</button>
                <button onclick="confirmDownload('excel')" class="flex-1 py-3 rounded-xl bg-[#10b981] text-white hover:bg-[#059669] transition-all shadow-lg shadow-green-500/20">Excel</button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-start mb-8">
    <div class="flex gap-3">
        <div class="w-10 h-10 bg-[#0194f3] rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20 text-lg mt-1">T</div>
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold tracking-tight dark:text-white leading-none">TCG <span class="text-[#0194f3]">AI</span></h1>
            <p class="text-[9px] md:text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 tracking-[0.15em] uppercase font-bold">QA Automation Tool</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
    <a href="about.php" target="_blank" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-[#0194f3] transition-colors">
        About
    </a>
        
        <button id="theme-toggle" class="p-2.5 rounded-xl bg-white dark:bg-[#181a20] border border-gray-200 dark:border-gray-800 shadow-sm transition-all hover:scale-110 active:scale-95">
            <span id="theme-toggle-dark-icon" class="hidden text-lg leading-none">🌙</span>
            <span id="theme-toggle-light-icon" class="hidden text-lg leading-none">☀️</span>
        </button>
    </div>
</div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-1/3 bg-white dark:bg-[#181a20] p-6 rounded-2xl h-fit shadow-sm border border-gray-100 dark:border-gray-800">
                <form action="generate.php" method="POST" onsubmit="showLoading()">
                    <div class="mb-5">
                        <label class="block text-[10px] font-bold mb-2 text-gray-400 uppercase tracking-widest">Nama Project / Fitur</label>
                        <input type="text" name="project_name" required class="w-full p-3.5 bg-gray-50 dark:bg-[#0d0f13] border border-gray-200 dark:border-gray-700 rounded-xl dark:text-gray-300 focus:outline-none transition-all" placeholder="e.g. Modul Transaksi">
                    </div>
                    <div class="mb-5">
                        <label class="block text-[10px] font-bold mb-2 text-gray-400 uppercase tracking-widest">Format Penulisan</label>
                        <select name="tc_format" class="w-full p-3.5 bg-gray-50 dark:bg-[#0d0f13] border border-gray-200 dark:border-gray-700 rounded-xl dark:text-gray-300 focus:outline-none transition-all cursor-pointer font-semibold text-sm">
                            <option value="BDD">BDD / Gherkin (Given, When, Then)</option>
                            <option value="ACTION_EXPECTED">Action & Expected</option>
                            <option value="STANDARD">Standard Steps (Langkah 1, 2, 3)</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-[10px] font-bold mb-2 text-gray-400 uppercase tracking-widest">Requirement (BOE)</label>
                        <textarea name="requirement" rows="8" required class="w-full p-3.5 bg-gray-50 dark:bg-[#0d0f13] border border-gray-200 dark:border-gray-700 rounded-xl dark:text-gray-300 focus:outline-none transition-all" placeholder="Tempel requirement di sini..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-[#0194f3] text-white py-4 rounded-xl text-xs font-bold tracking-[0.2em] uppercase shadow-lg hover:bg-[#0181d4] transition-all">Generate Test Case</button>
                </form>
            </div>

            <div class="w-full lg:w-2/3 flex flex-col">
                
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-6 gap-4 w-full">
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <div class="flex items-center justify-between gap-3 bg-white dark:bg-[#181a20] p-2 px-4 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Proyek:</span>
                            <select id="projectFilter" onchange="applyFilters()" class="bg-transparent border-none text-[#0194f3] text-xs font-bold focus:outline-none cursor-pointer w-full text-right sm:text-left">
                                <option value="ALL_PROJECTS">SEMUA PROYEK</option>
                                <?php
                                // Query terisolasi khusus toolbar agar aman
                                $qToolbar = $conn->query("SELECT DISTINCT project_name FROM test_cases ORDER BY project_name ASC");
                                if($qToolbar){ while($p = $qToolbar->fetch_assoc()){ echo "<option value='".htmlspecialchars($p['project_name'])."'>".strtoupper(htmlspecialchars($p['project_name']))."</option>"; } }
                                ?>
                            </select>
                        </div>
                        <div class="flex items-center justify-between gap-3 bg-white dark:bg-[#181a20] p-2 px-4 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm w-full sm:w-auto">
                            <span class="text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Format:</span>
                            <select id="formatFilter" onchange="applyFilters()" class="bg-transparent border-none text-[#0194f3] text-xs font-bold focus:outline-none cursor-pointer w-full text-right sm:text-left">
                                <option value="ALL_FORMATS">SEMUA FORMAT</option>
                                <option value="BDD">BDD</option>
                                <option value="ACTION_EXPECTED">ACTION & EXPECTED</option>
                                <option value="STANDARD">STANDARD</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto justify-end">
                        
                        <?php
                        $countAll = $conn->query("SELECT COUNT(*) FROM test_cases")->fetch_row()[0];
                        $countPos = $conn->query("SELECT COUNT(*) FROM test_cases WHERE test_type='POSITIVE'")->fetch_row()[0];
                        $countNeg = $conn->query("SELECT COUNT(*) FROM test_cases WHERE test_type='NEGATIVE'")->fetch_row()[0];
                        ?>
                        
                        <div class="flex w-full sm:w-auto justify-between gap-1 bg-gray-200/50 dark:bg-gray-800/50 p-1.5 rounded-xl border border-gray-100 dark:border-gray-800">
                            <button id="tab-ALL" onclick="setTabFilter('ALL')" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-[10px] font-bold transition-all bg-white dark:bg-[#12141a] text-[#0194f3] shadow-sm whitespace-nowrap">SEMUA [<?php echo $countAll; ?>]</button>
                            <button id="tab-POSITIVE" onclick="setTabFilter('POSITIVE')" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-[10px] font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all whitespace-nowrap">POS [<?php echo $countPos; ?>]</button>
                            <button id="tab-NEGATIVE" onclick="setTabFilter('NEGATIVE')" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-[10px] font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all whitespace-nowrap">NEG [<?php echo $countNeg; ?>]</button>
                        </div>

                        <div class="flex w-full sm:w-auto items-center justify-between sm:justify-end gap-3">
                            <div class="flex gap-1 bg-gray-200/50 dark:bg-gray-800/50 p-1.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                <button id="view-card-btn" onclick="toggleView('card')" class="px-5 py-2 rounded-lg text-[12px] font-bold transition-all bg-white dark:bg-[#12141a] shadow-sm" title="Tampilan Kartu">💳</button>
                                <button id="view-table-btn" onclick="toggleView('table')" class="px-5 py-2 rounded-lg text-[12px] font-bold transition-all hover:bg-white/50 dark:hover:bg-gray-700/50" title="Tampilan Tabel">📊</button>
                            </div>

                            <button onclick="openExportModal()" class="flex-1 sm:flex-none justify-center bg-[#0194f3] text-white hover:bg-[#0181d4] py-2.5 px-5 rounded-xl shadow-lg shadow-blue-500/20 text-[10px] font-bold transition-all flex items-center gap-2 uppercase tracking-widest">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Export
                            </button>
                        </div>
                    </div>
                </div>

                <div id="card-view-area" class="custom-scroll overflow-y-auto pr-2" style="max-height: 75vh;">
                    <?php
                    $res = $conn->query("SELECT * FROM test_cases ORDER BY id DESC");
                    if ($res && $res->num_rows > 0) {
                        while($row = $res->fetch_assoc()) {
                            $isPos = $row['test_type'] == 'POSITIVE';
                            $tcNumber = "TC-" . str_pad($row['id'], 4, "0", STR_PAD_LEFT);
                            $tcFormat = isset($row['tc_format']) ? $row['tc_format'] : 'STANDARD';
                            ?>
                            <div class="test-case-card mb-4" data-project="<?php echo htmlspecialchars($row['project_name']); ?>" data-format="<?php echo $tcFormat; ?>" data-type="<?php echo $row['test_type']; ?>">
                                <div class="bg-white dark:bg-[#181a20] border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                                    <div class="p-5 border-l-[6px] <?php echo $isPos ? 'border-green-400' : 'border-red-400'; ?>">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="text-[10px] font-black p-1 px-2 rounded-md bg-gray-100 dark:bg-[#0d0f13] text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700"><?php echo $tcNumber; ?></span>
                                                <span class="text-[9px] font-bold p-1 px-2 rounded-md <?php echo $isPos ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'; ?> uppercase"><?php echo $row['test_type']; ?></span>
                                                <span class="text-[9px] font-bold text-white px-2 py-1 bg-[#0194f3] rounded-md uppercase"><?php echo $tcFormat; ?></span>
                                            </div>
                                            <button onclick="copyToClipboard(this)" class="text-[9px] font-bold text-[#0194f3] hover:bg-blue-50 dark:hover:bg-blue-900/10 px-3 py-1.5 rounded-lg border border-blue-100 dark:border-blue-900/50 transition-all uppercase">Salin</button>
                                        </div>
                                        <h3 class="text-gray-800 dark:text-gray-200 text-sm font-bold mb-4"><?php echo htmlspecialchars($row['title']); ?></h3>
                                        
                                        <div class="flex flex-col sm:flex-row gap-4 mb-4">
                                            <div class="flex-1 bg-blue-50 dark:bg-blue-900/10 p-3 rounded-lg border border-blue-100 dark:border-blue-900/30">
                                                <span class="text-[9px] font-bold text-blue-400 block mb-1 uppercase">Precondition</span>
                                                <p class="text-xs text-gray-600 dark:text-gray-300"><?php echo htmlspecialchars($row['precondition'] ?? '-'); ?></p>
                                            </div>
                                            <div class="flex-1 bg-purple-50 dark:bg-purple-900/10 p-3 rounded-lg border border-purple-100 dark:border-purple-900/30">
                                                <span class="text-[9px] font-bold text-purple-400 block mb-1 uppercase">Data Test</span>
                                                <p class="text-xs text-gray-600 dark:text-gray-300"><?php echo htmlspecialchars($row['data_test'] ?? '-'); ?></p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-[#0d0f13] p-4 rounded-xl border border-gray-100 dark:border-gray-800 font-mono text-[12px] text-gray-600 dark:text-gray-400 whitespace-pre-line steps-content"><?php echo htmlspecialchars($row['steps']); ?></div>
                                        <div class="mt-4 flex items-start gap-3 pt-3 border-t border-gray-50 dark:border-gray-800">
                                            <span class="text-[9px] font-bold text-gray-300 dark:text-gray-600 mt-0.5 uppercase">Expected</span>
                                            <p class="text-xs text-gray-500 italic"><?php echo htmlspecialchars($row['expected_result']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } 
                    } else {
                        echo '<div class="text-center p-12 mt-10 border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl"><p class="text-gray-400 italic">Belum ada test case yang di-generate.</p></div>';
                    } ?>
                </div>

                <div id="table-view-area" class="hidden custom-scroll overflow-y-auto overflow-x-auto pr-2" style="max-height: 75vh;">
                    <div class="flex justify-end mb-4">
                        <button onclick="copyTableToExcel()" class="bg-[#10b981] hover:bg-[#059669] text-white px-4 py-2 rounded-lg text-xs font-bold shadow-lg transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            Salin Tabel ke Excel
                        </button>
                    </div>
                    <div class="bg-white dark:bg-[#181a20] rounded-xl border border-gray-200 dark:border-gray-800 overflow-x-auto custom-scroll w-full block">
                        <table id="tcTable" class="excel-table min-w-max">
                            <thead>
                                <tr>
                                    <th style="min-width: 90px;">No TC</th>
                                    <th style="min-width: 100px;">Format</th>
                                    <th style="min-width: 120px;">Fitur</th>
                                    <th style="min-width: 150px;">Scenario</th>
                                    <th style="min-width: 150px;">Precondition</th>
                                    <th style="min-width: 120px;">Data Test</th>
                                    <th style="min-width: 250px;">Test Step</th>
                                    <th style="min-width: 200px;">Expectation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($res && $res->num_rows > 0) {
                                    $res->data_seek(0);
                                    while($row = $res->fetch_assoc()) {
                                        $tcNumber = "TC-" . str_pad($row['id'], 4, "0", STR_PAD_LEFT);
                                        $tcFormat = isset($row['tc_format']) ? $row['tc_format'] : 'STANDARD';
                                        ?>
                                        <tr class="table-row-item" data-project="<?php echo htmlspecialchars($row['project_name']); ?>" data-format="<?php echo $tcFormat; ?>" data-type="<?php echo $row['test_type']; ?>">
                                            <td><?php echo $tcNumber; ?></td>
                                            <td><?php echo $tcFormat; ?></td>
                                            <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['precondition'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($row['data_test'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($row['steps']); ?></td>
                                            <td><?php echo htmlspecialchars($row['expected_result']); ?></td>
                                        </tr>
                                    <?php } 
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        function updateIcons() {
            if (document.documentElement.classList.contains('dark')) { darkIcon.classList.add('hidden'); lightIcon.classList.remove('hidden'); } 
            else { darkIcon.classList.remove('hidden'); lightIcon.classList.add('hidden'); }
        }
        updateIcons();
        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            updateIcons();
        });

        function showLoading() { document.getElementById('loadingOverlay').style.display = 'flex'; }
        
        function openExportModal() { document.getElementById('exportModal').classList.remove('hidden'); }
        function closeExportModal() { document.getElementById('exportModal').classList.add('hidden'); }
        function confirmDownload(type) {
            const project = document.getElementById('modalProjectSelect').value;
            const format = document.getElementById('modalFormatSelect').value;
            closeExportModal();
            
            if (type === 'pdf') {
                window.location.href = 'export_pdf.php?project=' + encodeURIComponent(project) + '&format=' + encodeURIComponent(format);
            } else if (type === 'excel') {
                window.location.href = 'export_excel.php?project=' + encodeURIComponent(project) + '&format=' + encodeURIComponent(format);
            }
        }

        function toggleView(view) {
            const cardArea = document.getElementById('card-view-area');
            const tableArea = document.getElementById('table-view-area');
            const cardBtn = document.getElementById('view-card-btn');
            const tableBtn = document.getElementById('view-table-btn');
            
            const activeClass = 'px-5 py-2 rounded-lg text-[12px] font-bold transition-all bg-white dark:bg-[#12141a] shadow-sm';
            const inactiveClass = 'px-5 py-2 rounded-lg text-[12px] font-bold transition-all hover:bg-white/50 dark:hover:bg-gray-700/50';

            if(view === 'card') {
                cardArea.classList.remove('hidden'); tableArea.classList.add('hidden');
                cardBtn.className = activeClass; tableBtn.className = inactiveClass;
            } else {
                cardArea.classList.add('hidden'); tableArea.classList.remove('hidden');
                cardBtn.className = inactiveClass; tableBtn.className = activeClass;
            }
        }

        let currentTab = 'ALL';
        function setTabFilter(tab) {
            currentTab = tab;
            document.querySelectorAll('button[id^="tab-"]').forEach(t => { 
                t.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-[10px] font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all whitespace-nowrap'; 
            });
            const active = document.getElementById('tab-' + tab);
            active.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-[10px] font-bold transition-all bg-white dark:bg-[#12141a] text-[#0194f3] shadow-sm whitespace-nowrap';
            applyFilters();
        }

        function applyFilters() {
            const proj = document.getElementById('projectFilter').value;
            const format = document.getElementById('formatFilter').value;
            
            document.querySelectorAll('.test-case-card').forEach(card => {
                const matchProj = (proj === 'ALL_PROJECTS' || card.dataset.project === proj);
                const matchFormat = (format === 'ALL_FORMATS' || card.dataset.format === format);
                const matchTab = (currentTab === 'ALL' || card.dataset.type === currentTab);
                card.style.display = (matchProj && matchFormat && matchTab) ? 'block' : 'none';
            });

            document.querySelectorAll('.table-row-item').forEach(row => {
                const matchProj = (proj === 'ALL_PROJECTS' || row.dataset.project === proj);
                const matchFormat = (format === 'ALL_FORMATS' || row.dataset.format === format);
                const matchTab = (currentTab === 'ALL' || row.dataset.type === currentTab);
                row.style.display = (matchProj && matchFormat && matchTab) ? 'table-row' : 'none';
            });
        }

        function copyToClipboard(btn) {
            const content = btn.closest('.test-case-card').querySelector('.steps-content').innerText;
            navigator.clipboard.writeText(content).then(() => {
                const originalText = btn.innerText; 
                btn.innerText = 'BERHASIL!'; 
                btn.style.color = '#10b981';
                setTimeout(() => { btn.innerText = originalText; btn.style.color = ''; }, 2000);
            });
        }

        function copyTableToExcel() {
            let table = document.getElementById('tcTable');
            let rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (row.style.display === 'none') { row.classList.add('no-copy'); }
            });

            var range = document.createRange();
            range.selectNode(table);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");
            window.getSelection().removeAllRanges();
            alert("✅ Tabel berhasil disalin!\nSilakan buka Excel, lalu tekan Ctrl+V (Paste).");
        }
    </script>
</body>
</html>
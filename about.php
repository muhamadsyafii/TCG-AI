<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me | Muhamad Syafii</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
        }
        body {
            background-color: #0f172a;
            color: #f8fafc;
        }
        .text-gradient {
            background: linear-gradient(to right, #3b82f6, #2dd4bf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased">

    <div class="max-w-5xl mx-auto px-6 py-20">
        
        <div class="mb-12">
            <a href="index.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 transition-all group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                <span class="font-medium">Back to Home</span>
            </a>
        </div>

        <div class="text-center mb-16">
            <div class="relative inline-block mb-6">
                <img src="https://github.com/muhamadsyafii.png" alt="Muhamad Syafii" class="w-32 h-32 rounded-3xl border-4 border-[#0194f3] shadow-2xl mx-auto object-cover">
                <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-4 border-[#0f172a]"></div>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-2">Muhamad <span class="text-[#0194f3]">Syafii</span></h1>
            <p class="text-xl text-gray-400 font-medium">Senior Android Engineer & Enthusiast Data Analyst</p>
            
            <div class="flex justify-center gap-6 mt-6">
                <a href="https://github.com/muhamadsyafii" target="_blank" class="text-gray-400 hover:text-white transition-all text-2xl"><i class="fab fa-github"></i></a>
                <a href="https://linkedin.com/in/muhamadsyafii4" target="_blank" class="text-gray-400 hover:text-blue-500 transition-all text-2xl"><i class="fab fa-linkedin"></i></a>
                <a href="https://muhamadsyafii.my.id" target="_blank" class="text-gray-400 hover:text-cyan-400 transition-all text-2xl"><i class="fas fa-globe"></i></a>
                <a href="mailto:muhamadsyafii4@gmail.com" class="text-gray-400 hover:text-red-400 transition-all text-2xl"><i class="fas fa-envelope"></i></a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <section class="glass-card p-8 rounded-3xl">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-user-astronaut text-[#0194f3]"></i> About Me
                    </h2>
                    <p class="text-gray-300 leading-relaxed text-lg mb-4">
                        Halo! Saya adalah seorang <strong>Software Engineer</strong> yang memiliki spesialisasi dalam pengembangan aplikasi <strong>Native Android</strong> selama lebih dari 6 tahun. Perjalanan karier saya didorong oleh rasa ingin tahu yang besar terhadap teknologi mobile dan bagaimana arsitektur yang bersih dapat menghasilkan produk yang luar biasa.
                    </p>
                    <p class="text-gray-300 leading-relaxed text-lg mb-4">
                        Saat ini, saya aktif berkontribusi di <strong>Panelo</strong>, sebuah perusahaan yang bergerak di bidang digital signage. Di sana, saya bertanggung jawab membangun ekosistem aplikasi yang stabil, mulai dari sinkronisasi data real-time hingga optimasi performa pada berbagai perangkat hardware signage melalui aplikasi <i>Panelo Player</i> dan <i>MCMS</i>.
                    </p>
                    <p class="text-gray-300 leading-relaxed text-lg">
                        Di luar pekerjaan korporat, saya adalah seorang pengembang game indie yang menyukai kesederhanaan. Saya mengelola portal <strong>Pilkupil.my.id</strong>, wadah di mana saya bereksperimen dengan game berbasis HTML yang bisa diakses siapa saja dengan mudah. Bagi saya, filosofi dalam bekerja itu sederhana: <i>tetap relevan dengan terus belajar</i>. Itulah alasan mengapa saat ini saya juga sedang antusias mengeksplorasi dunia <strong>Data Analysis</strong> untuk melengkapi kemampuan teknis saya.
                    </p>
                </section>

                <section class="glass-card p-8 rounded-3xl">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-link text-[#0194f3]"></i> Featured Links
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="http://pilkupil.my.id/" target="_blank" class="p-5 border border-gray-700 rounded-2xl hover:bg-gray-800/50 transition-all">
                            <h3 class="font-bold text-lg text-blue-400">Pilkupil Portal</h3>
                            <p class="text-sm text-gray-400 mt-2">Dunia game HTML kreatif seperti Bocil Hunter & Bajing Loencat.</p>
                        </a>
                        <a href="https://muhamadsyafii.my.id/" target="_blank" class="p-5 border border-gray-700 rounded-2xl hover:bg-gray-800/50 transition-all">
                            <h3 class="font-bold text-lg text-cyan-400">Main Portfolio</h3>
                            <p class="text-sm text-gray-400 mt-2">Wadah profil profesional dan showcase perjalanan karier saya.</p>
                        </a>
                        <div class="p-5 border border-gray-700 rounded-2xl bg-gray-800/20">
                            <h3 class="font-bold text-lg text-purple-400">Panelo Ecosystem</h3>
                            <p class="text-sm text-gray-400 mt-2">Solusi signage digital masa depan yang stabil dan terukur.</p>
                        </div>
                        <a href="https://github.com/muhamadsyafii" target="_blank" class="p-5 border border-gray-700 rounded-2xl hover:bg-gray-800/50 transition-all">
                            <h3 class="font-bold text-lg text-white">Open Source</h3>
                            <p class="text-sm text-gray-400 mt-2">Repositori publik, library KupilDown, dan eksperimen Rust/JNI.</p>
                        </a>
                    </div>
                </section>
            </div>

            <div class="space-y-8">
                <section class="glass-card p-8 rounded-3xl">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-microchip text-[#0194f3]"></i> Toolbox
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $skills = ['Kotlin', 'Jetpack Compose', 'Rust (JNI)', 'Java', 'PHP', 'Data Analysis', 'Git', 'SQL'];
                        foreach ($skills as $skill) {
                            echo "<span class='bg-blue-500/10 text-blue-400 px-3 py-1 rounded-full text-[11px] font-bold border border-blue-500/20 uppercase'>$skill</span>";
                        }
                        ?>
                    </div>
                </section>

                <section class="glass-card p-8 rounded-3xl">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-[#0194f3]"></i> Education
                    </h2>
                    <div>
                        <p class="font-bold text-gray-200">Magister (S2)</p>
                        <p class="text-sm text-gray-400">Ilmu Komputer</p>
                        <p class="text-xs text-blue-400 mt-1 uppercase font-bold tracking-widest">Universitas Budi Luhur</p>
                    </div>
                </section>

                <section class="glass-card p-6 rounded-3xl text-center">
                    <h2 class="text-xl font-bold mb-4">GitHub Stats</h2>
                    <img src="https://github-readme-stats.vercel.app/api?username=muhamadsyafii&show_icons=true&theme=dark&bg_color=0f172a&hide_border=true" alt="Stats" class="w-full">
                </section>
            </div>
        </div>

        <footer class="mt-20 text-center text-gray-500 text-sm border-t border-white/5 pt-10">
            <p class="mb-2 italic">"To infinity and beyond. 👨‍💻🚀"</p>
            <p>&copy; <?php echo date("Y"); ?> Muhamad Syafii. Crafted in Cirebon.</p>
        </footer>
    </div>

</body>
</html>
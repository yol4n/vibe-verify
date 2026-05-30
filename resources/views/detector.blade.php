<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VibeVerify - Detektor Berita Palsu Berbasis AI</title>
    <!-- Tailwind CSS (Aesthetic Styling) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <!-- Token CSRF untuk keamanan Request POST Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between">

    <!-- Header / Navbar -->
    <header class="bg-white/90 border-b border-slate-200 sticky top-0 z-50 shadow-sm backdrop-blur-md">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 text-white p-2.5 rounded-xl shadow-md shadow-indigo-100">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">VibeVerify</h1>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Hoax AI Detector Tool</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                    <span class="w-2 h-2 mr-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    #JuaraVibeCoding
                </span>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="max-w-3xl mx-auto px-4 py-12 flex-grow w-full">
        <!-- Hero Section -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                Verifikasi Berita Secara <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Real-Time</span>
            </h2>
            <p class="mt-3 max-w-lg mx-auto text-sm text-slate-500 leading-relaxed">
                Khawatir tentang berita palsu? Tempelkan teks atau artikel di bawah ini, dan sistem kecerdasan buatan (AI) kami akan menganalisis kebenarannya secara instan.
            </p>
        </div>

        <!-- Card Input Berita -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 sm:p-8 mb-8 transition-all hover:shadow-2xl">
            <form id="detectorForm" class="space-y-4">
                <div>
                    <label for="news_text" class="block text-xs font-bold uppercase text-slate-400 tracking-wider mb-2">Teks Berita yang dicurigai:</label>
                    <textarea 
                        id="news_text" 
                        name="news_text" 
                        rows="6" 
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-slate-700 placeholder-slate-400 text-sm leading-relaxed"
                        placeholder="Contoh: Breaking News! Mulai besok pagi pemerintah akan menutup semua akses internet untuk pemeliharaan jaringan nasional. Silakan bagikan informasi ini agar tidak tertinggal..."></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        id="btnSubmit" 
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-lg shadow-indigo-100 hover:scale-[1.02] active:scale-[0.98] w-full sm:w-auto">
                        <i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Mulai Analisis Berita
                    </button>
                </div>
            </form>
        </div>

        <!-- Indikator Loading (Akan muncul saat proses kirim data) -->
        <div id="loadingState" class="hidden text-center py-12 bg-white rounded-2xl border border-slate-100 shadow-md">
            <div class="inline-block relative w-12 h-12">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
            </div>
            <p class="mt-4 text-sm font-semibold text-slate-500 animate-pulse">Menghubungkan ke AI Engine, menganalisis struktur bahasa...</p>
        </div>

        <!-- Bagian Hasil Analisis (Akan muncul setelah data diterima) -->
        <div id="resultSection" class="hidden transform transition-all duration-500 opacity-0 scale-95">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                
                <!-- Header Status Hasil -->
                <div id="resultBanner" class="px-6 py-5 text-white flex items-center space-x-4">
                    <div id="resultIconBox" class="bg-white/20 p-3 rounded-xl backdrop-blur-sm text-2xl">
                        <!-- Icon disisipkan via Javascript -->
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-white/80">Skor & Deteksi Keaslian</span>
                        <h3 id="resultStatusText" class="text-xl font-extrabold tracking-tight"><!-- Teks disisipkan via JS --></h3>
                    </div>
                </div>

                <!-- Detail Analisis -->
                <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    
                    <!-- Lingkaran Skor -->
                    <div class="flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Skor Kredibilitas</span>
                        <div class="relative flex items-center justify-center">
                            <div class="w-28 h-28 rounded-full border-8 border-slate-100 flex items-center justify-center shadow-inner">
                                <span id="resultScore" class="text-3xl font-extrabold text-slate-800"><!-- Skor via JS -->%</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 text-center mt-3 px-2">Semakin mendekati 100%, konten berita semakin dapat dipercaya.</p>
                    </div>

                    <!-- Detail Alasan dan Rekomendasi -->
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <h4 class="text-xs font-bold uppercase text-indigo-600 tracking-wider mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Penjelasan Analisis:</h4>
                            <p id="resultReason" class="text-slate-700 text-sm leading-relaxed font-medium">
                                <!-- Penjelasan via JS -->
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100">
                            <h4 class="text-xs font-bold uppercase text-teal-600 tracking-wider mb-1"><i class="fa-solid fa-circle-check mr-1"></i> Rekomendasi Tindakan:</h4>
                            <p id="resultRecommendation" class="text-slate-600 text-sm italic leading-relaxed">
                                <!-- Rekomendasi via JS -->
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400 font-medium w-full">
        <p>&copy; 2026 VibeVerify. Powered by Google Gemini 1.5 Flash. Dibuat dengan bangga untuk #JuaraVibeCoding.</p>
        <p class="mt-2 text-xs text-slate-400">
    Project by <span class="font-semibold text-slate-500">Yolan Dame Simbolon</span> 
</p>
    </footer>

    <!-- Script Javascript Asinkronus -->
<script>
    document.getElementById('detectorForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const newsText = document.getElementById('news_text').value.trim();
        const btnSubmit = document.getElementById('btnSubmit');
        const loadingState = document.getElementById('loadingState');
        const resultSection = document.getElementById('resultSection');

        if (newsText.length < 30) {
            alert('Tolong masukkan teks berita minimal 30 karakter agar AI kami dapat memberikan analisis yang komprehensif.');
            return;
        }

        btnSubmit.disabled = true;
        loadingState.classList.remove('hidden');
        resultSection.classList.add('hidden');
        resultSection.classList.remove('opacity-100', 'scale-100');
        resultSection.classList.add('opacity-0', 'scale-95');

        try {
            const response = await fetch('/analyze', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    news_text: newsText
                })
            });

            const result = await response.json();

            if (!response.ok) {
                alert(result.message || 'Terjadi gangguan pada server. Silakan coba lagi.');
                loadingState.classList.add('hidden');
                return;
            }

            if (result.success && result.data) {
                const data = result.data;

                document.getElementById('resultScore').innerText = data.skor;
                document.getElementById('resultReason').innerText = data.alasan;
                document.getElementById('resultRecommendation').innerText = data.rekomendasi;

                const banner = document.getElementById('resultBanner');
                const iconBox = document.getElementById('resultIconBox');
                const statusText = document.getElementById('resultStatusText');

                banner.className = "px-6 py-5 text-white flex items-center space-x-4";

                if (data.status === 'HOAX') {
                    banner.classList.add('bg-gradient-to-r', 'from-rose-500', 'to-red-600');
                    iconBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                    statusText.innerText = 'TERINDIKASI HOAX / PALSU';
                } else if (data.status === 'POTENSI_HOAX') {
                    banner.classList.add('bg-gradient-to-r', 'from-amber-500', 'to-orange-600');
                    iconBox.innerHTML = '<i class="fa-solid fa-circle-question"></i>';
                    statusText.innerText = 'POTENSI HOAX / MERAGUKAN';
                } else {
                    banner.classList.add('bg-gradient-to-r', 'from-emerald-500', 'to-teal-600');
                    iconBox.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
                    statusText.innerText = 'BERITA VALID / AKURAT';
                }

                loadingState.classList.add('hidden');
                resultSection.classList.remove('hidden');

                setTimeout(() => {
                    resultSection.classList.remove('opacity-0', 'scale-95');
                    resultSection.classList.add('opacity-100', 'scale-100');
                }, 50);

            } else {
                alert(result.message || 'Terjadi gangguan sistem. Silakan coba sesaat lagi.');
                loadingState.classList.add('hidden');
            }

        } catch (error) {
            console.error(error);
            alert('Gagal menghubungi server analisis. Silakan refresh halaman atau coba beberapa saat lagi.');
            loadingState.classList.add('hidden');
        } finally {
            btnSubmit.disabled = false;
        }
    });
</script>
</body>
</html>
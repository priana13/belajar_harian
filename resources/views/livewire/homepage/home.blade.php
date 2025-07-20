<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Pembelajaran Modern</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 2rem 1.5rem 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="90" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="20" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-100vh) rotate(360deg); }
        }

        .welcome-text {
            color: white;
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 10;
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            position: relative;
            z-index: 10;
        }

        .content {
            padding: 2rem 1.5rem;
            margin-top: -1rem;
            background: white;
            border-radius: 2rem 2rem 0 0;
            position: relative;
            z-index: 5;
        }

        .auth-section {
            text-align: center;
            padding: 2rem 0;
        }

        .hero-image {
            width: 200px;
            height: 200px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #ddd6fe 0%, #c7d2fe 100%);
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #3b82f6;
        }

        .btn {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 2rem 0 1rem;
        }

        .material-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .material-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .material-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .material-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ddd6fe 0%, #c7d2fe 100%);
            color: #3b82f6;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            margin-bottom: 1rem;
        }

        .material-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .material-subtitle {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .material-meta {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .meta-tag {
            background: #f8fafc;
            color: #475569;
            padding: 0.5rem 0.75rem;
            border-radius: 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .audio-player {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .player-info {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .track-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .track-subtitle {
            color: #64748b;
            font-size: 0.9rem;
        }

        .player-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .control-btn {
            background: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .control-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .play-btn {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .progress-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .time-display {
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .progress-bar {
            flex: 1;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            position: relative;
            cursor: pointer;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 3px;
            width: 0%;
            transition: width 0.3s ease;
        }

        .progress-handle {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            background: #3b82f6;
            border-radius: 50%;
            cursor: pointer;
        }

        .exam-card {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border-radius: 1.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #f59e0b;
        }

        .exam-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 0.5rem;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            backdrop-filter: blur(8px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 2rem;
            padding: 2rem;
            margin: 1rem;
            max-width: 400px;
            width: 100%;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: white;
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-around;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            color: #64748b;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .nav-item.active {
            color: #3b82f6;
            background: #eff6ff;
        }

        .nav-icon {
            font-size: 1.25rem;
        }

        .nav-label {
            font-size: 0.7rem;
            font-weight: 500;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="welcome-text">Ahlan wa Sahlan</h1>
            <p class="welcome-subtitle">Platform Pembelajaran Digital</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Auth Section (Guest) -->
            <div id="auth-section" class="auth-section">
                <div class="hero-image">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <button class="btn btn-primary">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>
                    Masuk
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>
                    Daftar
                </button>
            </div>

            <!-- Main Content (User Logged In) -->
            <div id="main-content" style="display: none;">
                <!-- Available Materials -->
                <div class="section">
                    <h2 class="section-title">Materi Tersedia</h2>
                    
                    <div class="material-card">
                        <div class="material-badge">Kategori Ilmu</div>
                        <h3 class="material-title">EKJ-001 - Pengenalan Akhlak Islami</h3>
                        <p class="material-subtitle">Mulai Belajar: 25 Jul 2025</p>
                        <button class="btn btn-primary">
                            <i class="fas fa-play" style="margin-right: 0.5rem;"></i>
                            Ikuti Materi
                        </button>
                    </div>

                    <div class="material-card">
                        <div class="material-badge">Kategori Fiqh</div>
                        <h3 class="material-title">FQH-002 - Thaharah dan Shalat</h3>
                        <p class="material-subtitle">Mulai Belajar: 01 Agu 2025</p>
                        <button class="btn btn-primary">
                            <i class="fas fa-play" style="margin-right: 0.5rem;"></i>
                            Ikuti Materi
                        </button>
                    </div>
                </div>

                <!-- Today's Material -->
                <div class="section">
                    <h2 class="section-title">Materi Hari Ini</h2>
                    
                    <div class="material-card">
                        <div class="material-badge">Akhlak</div>
                        <h3 class="material-title">Adab Dalam Berinteraksi</h3>
                        <p class="material-subtitle">EKJ-001: Pengenalan Akhlak Islami</p>
                        
                        <div class="material-meta">
                            <span class="meta-tag">Pertemuan 3</span>
                            <span class="meta-tag">20 Jul 2025</span>
                        </div>

                        <!-- Audio Player -->
                        <div class="audio-player">
                            <div class="player-info">
                                <div class="track-title">Adab Dalam Berinteraksi</div>
                                <div class="track-subtitle">Pengenalan Akhlak Islami</div>
                            </div>
                            
                            <div class="player-controls">
                                <button class="control-btn" id="prev-btn">
                                    <i class="fas fa-step-backward"></i>
                                </button>
                                <button class="control-btn play-btn" id="play-btn">
                                    <i class="fas fa-play"></i>
                                </button>
                                <button class="control-btn" id="next-btn">
                                    <i class="fas fa-step-forward"></i>
                                </button>
                            </div>

                            <div class="progress-container">
                                <span class="time-display" id="current-time">00:00</span>
                                <div class="progress-bar" id="progress-bar">
                                    <div class="progress-fill" id="progress-fill"></div>
                                    <div class="progress-handle" id="progress-handle"></div>
                                </div>
                                <span class="time-display" id="total-time">00:00</span>
                            </div>
                        </div>

                        <button class="btn btn-primary pulse">
                            <i class="fas fa-clipboard-question" style="margin-right: 0.5rem;"></i>
                            Kerjakan Soal
                        </button>
                    </div>
                </div>

                <!-- Today's Exam -->
                <div class="section">
                    <h2 class="section-title">Ujian Hari Ini</h2>
                    
                    <div class="exam-card">
                        <h3 class="exam-title">Ujian Pekanan 1</h3>
                        <p class="material-subtitle">EKJ-001 - Pengenalan Akhlak Islami</p>
                        <button class="btn btn-primary" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); margin-top: 1rem;">
                            <i class="fas fa-pencil-alt" style="margin-right: 0.5rem;"></i>
                            Kerjakan Soal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="#" class="nav-item active">
                <i class="nav-icon fas fa-home"></i>
                <span class="nav-label">Beranda</span>
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-book"></i>
                <span class="nav-label">Materi</span>
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <span class="nav-label">Ujian</span>
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-user"></i>
                <span class="nav-label">Profil</span>
            </a>
        </nav>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Simulate login/logout
        let isLoggedIn = false;
        const authSection = document.getElementById('auth-section');
        const mainContent = document.getElementById('main-content');
        
        // Audio player functionality
        let isPlaying = false;
        let currentTime = 0;
        let totalTime = 300; // 5 minutes example
        
        const playBtn = document.getElementById('play-btn');
        const currentTimeDisplay = document.getElementById('current-time');
        const totalTimeDisplay = document.getElementById('total-time');
        const progressFill = document.getElementById('progress-fill');
        
        // Update total time display
        totalTimeDisplay.textContent = formatTime(totalTime);
        
        playBtn.addEventListener('click', () => {
            isPlaying = !isPlaying;
            if (isPlaying) {
                playBtn.innerHTML = '<i class="fas fa-pause"></i>';
                startProgress();
            } else {
                playBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        });
        
        function startProgress() {
            if (isPlaying && currentTime < totalTime) {
                currentTime++;
                updateProgress();
                setTimeout(startProgress, 1000);
            } else if (currentTime >= totalTime) {
                isPlaying = false;
                playBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        }
        
        function updateProgress() {
            const progress = (currentTime / totalTime) * 100;
            progressFill.style.width = progress + '%';
            currentTimeDisplay.textContent = formatTime(currentTime);
        }
        
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        
        // Toggle between auth and main content
        document.querySelector('.btn-primary').addEventListener('click', () => {
            if (!isLoggedIn) {
                isLoggedIn = true;
                authSection.style.display = 'none';
                mainContent.style.display = 'block';
            }
        });
        
        // Show main content by default for demo
        setTimeout(() => {
            isLoggedIn = true;
            authSection.style.display = 'none';
            mainContent.style.display = 'block';
        }, 1000);
    </script>
</body>
</html>
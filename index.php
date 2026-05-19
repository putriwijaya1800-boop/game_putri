<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastel Block Blast Adventure ✨</title>
    <!-- Google Fonts untuk font yang lucu dan bulat -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #FFF0F5; /* Lavender Blush */
            --panel-color: #FFE4E1; /* Misty Rose */
            --text-color: #6B4E71; /* Deep Pastel Purple */
            --accent-pink: #FFB6C1; /* Light Pink */
            --accent-dark-pink: #FF69B4; /* Hot Pink untuk tombol */
            --white: #FFFFFF;
        }

        * {
            box-sizing: border-box;
            user-select: none;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Quicksand', sans-serif;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            background-image: radial-gradient(circle, #ffffff 10%, transparent 10%), radial-gradient(circle, #ffffff 10%, transparent 10%);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
        }

        h1 {
            font-family: 'Fredoka One', cursive;
            color: var(--accent-dark-pink);
            text-shadow: 3px 3px 0px var(--white);
            margin-bottom: 10px;
            font-size: 2rem;
            text-align: center;
        }

        /* Container Utama */
        .game-container {
            background: var(--panel-color);
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 10px 0px rgba(221, 160, 221, 0.4);
            border: 4px solid var(--white);
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 450px;
            width: 95%;
            position: relative;
        }

        /* Dashboard Skor & Level */
        .stats-panel {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 15px;
            background: var(--white);
            padding: 10px 20px;
            border-radius: 20px;
            border: 3px solid var(--accent-pink);
        }

        .stat-box {
            text-align: center;
        }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--accent-pink);
        }

        .stat-value {
            font-family: 'Fredoka One', cursive;
            font-size: 1.4rem;
            color: var(--text-color);
        }

        /* Area Game */
        canvas {
            background: #FFF9FA;
            border-radius: 20px;
            border: 4px solid var(--white);
            box-shadow: inset 0 5px 10px rgba(0,0,0,0.05);
            cursor: grab;
        }

        canvas:active {
            cursor: grabbing;
        }

        /* Tempat Pilihan Blok */
        .blocks-preview {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            height: 120px;
            margin-top: 15px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            border: 2px dashed var(--accent-pink);
        }

        /* Overlay Animasi Menang / Kalah */
        .overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 240, 245, 0.9);
            border-radius: 26px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: all 0.4s ease;
            z-index: 10;
        }

        .overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .overlay-emoji {
            font-size: 5rem;
            animation: bounce 0.6s infinite alternate;
        }

        .overlay-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2rem;
            margin: 15px 0;
            color: var(--text-color);
        }

        .btn {
            background: var(--accent-dark-pink);
            color: white;
            border: none;
            padding: 12px 30px;
            font-family: 'Fredoka One', cursive;
            font-size: 1.2rem;
            border-radius: 20px;
            cursor: pointer;
            box-shadow: 0 5px 0 #D14785;
            transition: transform 0.1s;
        }

        .btn:active {
            transform: translateY(5px);
            box-shadow: 0 0px 0 #D14785;
        }

        /* Animasi Lucu */
        @keyframes bounce {
            from { transform: translateY(0) scale(1); }
            to { transform: translateY(-20px) scale(0.95); }
        }

        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .pop-effect {
            animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>
<body>

    <h1>✨ Block Blast: Pastel Magic ✨</h1>

    <div class="game-container">
        <!-- Panel Stats -->
        <div class="stats-panel">
            <div class="stat-box">
                <div class="stat-label">Level</div>
                <div id="level-val" class="stat-value">1</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Skor Kamu</div>
                <div id="score-val" class="stat-value">0</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Terbaik</div>
                <div id="best-val" class="stat-value">0</div>
            </div>
        </div>

        <!-- Canvas Game Utama -->
        <canvas id="gameCanvas" width="360" height="360"></canvas>

        <!-- Tempat Pilihan Blok Bawah -->
        <canvas id="previewCanvas" width="360" height="110"></canvas>

        <!-- Pop-up Overlay Menang (Naik Level) / Kalah -->
        <div id="game-overlay" class="overlay">
            <div id="overlay-icon" class="overlay-emoji">🐱</div>
            <h2 id="overlay-msg" class="overlay-title">Luar Biasa!</h2>
            <button id="overlay-btn" class="btn">Main Lagi</button>
        </div>
    </div>

    <script>
        // --- KONFIGURASI GAME ---
        const GRID_SIZE = 8; // Grid 8x8 ala Block Blast modern
        const CANVAS_SIZE = 360;
        const CELL_SIZE = CANVAS_SIZE / GRID_SIZE;
        
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const previewCanvas = document.getElementById('previewCanvas');
        const pCtx = previewCanvas.getContext('2d');

        // Warna Pastel yang Sangat Menggemaskan
        const colors = [
            '#FFB7B2', // Pastel Coral
            '#FFDAC1', // Pastel Peach
            '#E2F0CB', // Pastel Lime
            '#B5EAD7', // Pastel Mint
            '#C7CEEA', // Pastel Periwinkle
            '#FFC6FF', // Pastel Pink Candy
            '#BDB2FF'  // Pastel Purple Blue
        ];

        let grid = Array(GRID_SIZE).fill().map(() => Array(GRID_SIZE).fill(null));
        let score = 0;
        let highScore = localStorage.getItem('pastel_highscore') || 0;
        let currentLevel = 1;
        let availableBlocks = [];
        let draggedBlock = null;
        let dragOffsetX = 0;
        let dragOffsetY = 0;
        let mouseX = 0;
        let mouseY = 0;

        document.getElementById('best-val').innerText = highScore;

        // --- DEFINISI BENTUK BLOK (SHAPES) ---
        const shapes = [
            [[1]], // Kotak 1x1
            [[1, 1]], // Baris 1x2
            [[1, 1, 1]], // Baris 1x3
            [[1, 1], [1, 1]], // Kotak 2x2
            [[1, 1, 0], [0, 1, 1]], // Bentuk Z
            [[0, 1, 1], [1, 1, 0]], // Bentuk S
            [[1, 1, 1], [0, 1, 0]], // Bentuk T
            [[1, 1, 1], [1, 0, 0]], // Bentuk L
        ];

        class Block {
            constructor(shape, color, id) {
                this.shape = shape;
                this.color = color;
                this.id = id;
                this.rows = shape.length;
                this.cols = shape[0].length;
                this.x = 0; // Posisi x untuk rendering di preview / dragging
                this.y = 0; // Posisi y
                this.isDragged = false;
                this.scale = 0.7; // Diperkecil di panel bawah
            }
        }

        // --- LOGIKA GAMEPLAY ---
        function initGame() {
            grid = Array(GRID_SIZE).fill().map(() => Array(GRID_SIZE).fill(null));
            score = 0;
            currentLevel = 1;
            updateStats();
            generateNewBlocks();
            hideOverlay();
            draw();
        }

        function generateNewBlocks() {
            availableBlocks = [];
            for (let i = 0; i < 3; i++) {
                const randomShape = shapes[Math.floor(Math.random() * shapes.length)];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                const block = new Block(randomShape, randomColor, i);
                // Atur posisi awal di canvas bawah
                block.x = i * 120 + 25;
                block.y = 20;
                availableBlocks.push(block);
            }
        }

        function updateStats() {
            document.getElementById('score-val').innerText = score;
            document.getElementById('level-val').innerText = currentLevel;
            if (score > highScore) {
                highScore = score;
                localStorage.setItem('pastel_highscore', highScore);
                document.getElementById('best-val').innerText = highScore;
            }
            
            // Mekanisme Naik Level setiap kelipatan 300 poin
            let targetLevel = Math.floor(score / 300) + 1;
            if (targetLevel > currentLevel) {
                currentLevel = targetLevel;
                triggerCuteWin();
            }
        }

        // --- PROSES MENGGAMBAR (RENDER) ---
        function drawGrid() {
            ctx.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
            for (let r = 0; r < GRID_SIZE; r++) {
                for (let c = 0; c < GRID_SIZE; c++) {
                    ctx.fillStyle = '#FFF9FA';
                    ctx.strokeStyle = '#FFE4E1';
                    ctx.lineWidth = 2;
                    ctx.fillRect(c * CELL_SIZE, r * CELL_SIZE, CELL_SIZE, CELL_SIZE);
                    ctx.strokeRect(c * CELL_SIZE, r * CELL_SIZE, CELL_SIZE, CELL_SIZE);

                    // Gambar blok yang sudah terpasang tetap
                    if (grid[r][c]) {
                        drawRoundedRect(ctx, c * CELL_SIZE + 2, r * CELL_SIZE + 2, CELL_SIZE - 4, CELL_SIZE - 4, 8, grid[r][c]);
                        // Beri aksen mata lucu mini di blok
                        drawCuteEyes(ctx, c * CELL_SIZE + CELL_SIZE/2, r * CELL_SIZE + CELL_SIZE/2);
                    }
                }
            }
        }

        function drawPreviews() {
            pCtx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
            availableBlocks.forEach(block => {
                if (!block.isDragged) {
                    renderBlockOnCanvas(pCtx, block, block.x, block.y, block.scale);
                }
            });
        }

        function renderBlockOnCanvas(context, block, startX, startY, scale) {
            const size = CELL_SIZE * scale;
            for (let r = 0; r < block.rows; r++) {
                for (let c = 0; c < block.cols; c++) {
                    if (block.shape[r][c]) {
                        drawRoundedRect(context, startX + c * size, startY + r * size, size - 2, size - 2, 6, block.color);
                    }
                }
            }
        }

        // Helper menggambar kotak rounded yang halus dan modern
        function drawRoundedRect(context, x, y, width, height, radius, color) {
            context.fillStyle = color;
            context.beginPath();
            context.moveTo(x + radius, y);
            context.lineTo(x + width - radius, y);
            context.quadraticCurveTo(x + width, y, x + width, y + radius);
            context.lineTo(x + width, y + height - radius);
            context.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            context.lineTo(x + radius, y + height);
            context.quadraticCurveTo(x, y + height, x, y + height - radius);
            context.lineTo(x, y + radius);
            context.quadraticCurveTo(x, y, x + radius, y);
            context.closePath();
            context.fill();
            // Efek Highlight Putih Glossy atas (Bikin makin menggemaskan)
            context.fillStyle = 'rgba(255,255,255,0.4)';
            context.fillRect(x + 2, y + 2, width - 4, 4);
        }

        function drawCuteEyes(context, cx, cy) {
            context.fillStyle = '#6B4E71';
            context.beginPath();
            context.arc(cx - 6, cy - 2, 3, 0, Math.PI * 2);
            context.arc(cx + 6, cy - 2, 3, 0, Math.PI * 2);
            context.fill();
            // Pipi Merona (Blush)
            context.fillStyle = 'rgba(255, 105, 180, 0.4)';
            context.beginPath();
            context.arc(cx - 10, cy + 4, 3, 0, Math.PI * 2);
            context.arc(cx + 10, cy + 4, 3, 0, Math.PI * 2);
            context.fill();
        }

        function draw() {
            drawGrid();
            drawPreviews();
            
            // Gambar blok yang sedang ditarik di atas segalanya
            if (draggedBlock) {
                renderBlockOnCanvas(ctx, draggedBlock, mouseX - dragOffsetX, mouseY - dragOffsetY, 1.0);
            }
        }

        // --- CHECKER & LOGIKA ELEMINASI BARIS ---
        function checkLines() {
            let rowsToRemove = [];
            let colsToRemove = [];

            // Cek baris horizontal penuh
            for (let r = 0; r < GRID_SIZE; r++) {
                if (grid[r].every(cell => cell !== null)) rowsToRemove.push(r);
            }
            // Cek baris vertikal penuh
            for (let c = 0; c < GRID_SIZE; c++) {
                let colFull = true;
                for (let r = 0; r < GRID_SIZE; r++) {
                    if (grid[r][c] === null) { colFull = false; break; }
                }
                if (colFull) colsToRemove.push(c);
            }

            let cleared = rowsToRemove.length + colsToRemove.length;
            if (cleared > 0) {
                // Beri Skor Tanpa Batas! Kombo ganda dapat poin ekstra melimpah
                score += cleared * 100 + (cleared - 1) * 50;
                
                // Animasi Hancur (efek visual mengosongkan grid)
                rowsToRemove.forEach(r => grid[r].fill(null));
                colsToRemove.forEach(c => {
                    for (let r = 0; r < GRID_SIZE; r++) grid[r][c] = null;
                });
                
                updateStats();
            }

            // Jika 3 pilihan blok habis, munculkan 3 lagi baru
            if (availableBlocks.length === 0) {
                generateNewBlocks();
            }

            // Cek kondisi kalah (Game Over)
            if (checkGameOver()) {
                triggerCuteLose();
            }
        }

        function checkGameOver() {
            if (availableBlocks.length === 0) return false;
            
            // Uji setiap blok pilihan apakah masih bisa ditempatkan di sisa ruang kosong grid
            for (let block of availableBlocks) {
                for (let r = 0; r <= GRID_SIZE - block.rows; r++) {
                    for (let c = 0; c <= GRID_SIZE - block.cols; c++) {
                        let bisaMuat = true;
                        for (let br = 0; br < block.rows; br++) {
                            for (let bc = 0; bc < block.cols; bc++) {
                                if (block.shape[br][bc] && grid[r + br][c + bc] !== null) {
                                    bisaMuat = false;
                                    break;
                                }
                            }
                            if (!bisaMuat) break;
                        }
                        if (bisaMuat) return false; // Masih ada tempat aman!
                    }
                }
            }
            return true; // Tidak ada tempat tersisa, Game Over!
        }

        // --- ANIMASI & POP UP EMOSI LUCU ---
        const overlay = document.getElementById('game-overlay');
        const overlayIcon = document.getElementById('overlay-icon');
        const overlayMsg = document.getElementById('overlay-msg');
        const overlayBtn = document.getElementById('overlay-btn');

        const winEmojis = ['(◌𖠏◌) ✨', '٩(ˊᗜˋ*)و', '(っ˘ڡ˘ς) 💕', '₍ᐢ. ̫ .ᐢ₎ 🍰'];
        const loseEmojis = ['(⌯˃̶᷄ ﹏ ˂̶᷄⌯) 😭', '( ｡•_•｡) 💔', '(=🦭=) Ups!', '(✖╭╮✖)'];

        function triggerCuteWin() {
            overlayIcon.innerText = winEmojis[Math.floor(Math.random() * winEmojis.length)];
            overlayMsg.innerText = `Level Up! Level ${currentLevel} ✨`;
            overlayBtn.innerText = "Lanjutkan Seru!";
            overlayBtn.onclick = hideOverlay;
            overlay.classList.add('active');
        }

        function triggerCuteLose() {
            overlayIcon.innerText = loseEmojis[Math.floor(Math.random() * loseEmojis.length)];
            overlayMsg.innerText = `Skor Akhir: ${score} 🐾`;
            overlayBtn.innerText = "Coba Lagi Gemas";
            overlayBtn.onclick = initGame;
            overlay.classList.add('active');
        }

        function hideOverlay() {
            overlay.classList.remove('active');
        }

        // --- KONTROL DRAG AND DROP (MOUSE & SENTUHAN HP) ---
        function getCanvasMousePos(canvasDom, clientX, clientY) {
            const rect = canvasDom.getBoundingClientRect();
            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        // Event saat mulai menekan (Mulai Drag)
        function handleStart(clientX, clientY) {
            const pPos = getCanvasMousePos(previewCanvas, clientX, clientY);
            
            // Cek apakah klik mengenai salah satu dari 3 blok di bawah
            for (let i = availableBlocks.length - 1; i >= 0; i--) {
                let block = availableBlocks[i];
                let bSize = CELL_SIZE * block.scale;
                let width = block.cols * bSize;
                let height = block.rows * bSize;

                if (pPos.x >= block.x && pPos.x <= block.x + width &&
                    pPos.y >= block.y && pPos.y <= block.y + height) {
                    
                    draggedBlock = block;
                    block.isDragged = true;
                    // Ambil koordinat relatif sentuhan agar blok tidak melompat mendadak saat ditarik
                    dragOffsetX = (pPos.x - block.x) / block.scale;
                    dragOffsetY = (pPos.y - block.y) / block.scale;
                    
                    const mainPos = getCanvasMousePos(canvas, clientX, clientY);
                    mouseX = mainPos.x;
                    mouseY = mainPos.y;
                    break;
                }
            }
            draw();
        }

        // Event saat bergerak (Proses Dragging)
        function handleMove(clientX, clientY) {
            if (!draggedBlock) return;
            const mainPos = getCanvasMousePos(canvas, clientX, clientY);
            mouseX = mainPos.x;
            mouseY = mainPos.y;
            draw();
        }

        // Event saat melepas tekanan (Drop Blok)
        function handleEnd() {
            if (!draggedBlock) return;

            // Hitung posisi koordinat kolom dan baris grid tempat melepas blok
            let dropX = mouseX - dragOffsetX;
            let dropY = mouseY - dragOffsetY;
            let targetGridCol = Math.round(dropX / CELL_SIZE);
            let targetGridRow = Math.round(dropY / CELL_SIZE);

            let placementValid = true;

            // Validasi penempatan di dalam grid aman
            if (targetGridRow >= 0 && targetGridRow + draggedBlock.rows <= GRID_SIZE &&
                targetGridCol >= 0 && targetGridCol + draggedBlock.cols <= GRID_SIZE) {
                
                // Periksa tabrakan kotak lain yang sudah terisi
                for (let r = 0; r < draggedBlock.rows; r++) {
                    for (let c = 0; c < draggedBlock.cols; c++) {
                        if (draggedBlock.shape[r][c] && grid[targetGridRow + r][targetGridCol + c] !== null) {
                            placementValid = false;
                        }
                    }
                }
            } else {
                placementValid = false;
            }

            if (placementValid) {
                // Masukkan bentuk ke dalam array grid utama
                for (let r = 0; r < draggedBlock.rows; r++) {
                    for (let c = 0; c < draggedBlock.cols; c++) {
                        if (draggedBlock.shape[r][c]) {
                            grid[targetGridRow + r][targetGridCol + c] = draggedBlock.color;
                        }
                    }
                }
                score += draggedBlock.rows * draggedBlock.cols * 10; // Tambah skor dasar per blok tempel
                updateStats();
                
                // Hapus blok terpakai dari opsi bawah
                availableBlocks = availableBlocks.filter(b => b.id !== draggedBlock.id);
                draggedBlock = null;
                checkLines();
            } else {
                // Gagal taruh, kembalikan posisi blok semula dengan animasi mulus
                draggedBlock.isDragged = false;
                draggedBlock = null;
            }
            draw();
        }

        // --- LISTENER UNTUK DESKTOP PC ---
        canvas.addEventListener('mousedown', e => handleStart(e.clientX, e.clientY));
        previewCanvas.addEventListener('mousedown', e => handleStart(e.clientX, e.clientY));
        window.addEventListener('mousemove', e => handleMove(e.clientX, e.clientY));
        window.addEventListener('mouseup', handleEnd);

        // --- LISTENER MOBILE / RESPONSIVE HP ---
        canvas.addEventListener('touchstart', e => { handleStart(e.touches[0].clientX, e.touches[0].clientY); e.preventDefault(); }, {passive: false});
        previewCanvas.addEventListener('touchstart', e => { handleStart(e.touches[0].clientX, e.touches[0].clientY); e.preventDefault(); }, {passive: false});
        window.addEventListener('touchmove', e => { handleMove(e.touches[0].clientX, e.touches[0].clientY); }, {passive: true});
        window.addEventListener('touchend', handleEnd);

        // Jalankan game pertama kali
        initGame();
    </script>
</body>
</html>
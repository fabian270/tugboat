<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Snake</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            text-shadow: 0 0 20px #00ff88, 0 0 40px #00ff88;
            letter-spacing: 5px;
        }

        .game-info {
            display: flex;
            gap: 30px;
            margin-bottom: 15px;
            font-size: 1.1rem;
            align-items: center;
        }

        .score-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 20px;
            border-radius: 10px;
            border: 2px solid #00ff88;
        }

        .score-box span {
            color: #00ff88;
            font-weight: bold;
        }

        .top10-btn {
            background: #ff6b35;
            color: #fff;
            border: none;
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .top10-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px #ff6b35;
        }

        #gameCanvas {
            border: 4px solid #00ff88;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
            background: #0a0a15;
        }

        .controls {
            margin-top: 15px;
            text-align: center;
            color: #aaa;
            font-size: 0.9rem;
        }

        .controls kbd {
            background: #333;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 0 3px;
            border: 1px solid #555;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal {
            background: rgba(20, 20, 40, 0.98);
            padding: 40px 50px;
            border-radius: 20px;
            border: 3px solid #00ff88;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .modal h2 {
            color: #00ff88;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .modal p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .modal .record-score {
            color: #ffff00;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 0 0 10px #ffff00;
        }

        .modal input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            font-size: 1.1rem;
            border: 2px solid #00ff88;
            border-radius: 8px;
            background: #0a0a15;
            color: #fff;
            margin-bottom: 10px;
        }

        .modal input[type="text"]:focus {
            outline: none;
            box-shadow: 0 0 10px #00ff88;
        }

        .modal .char-count {
            text-align: right;
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 20px;
        }

        .modal .error-msg {
            color: #ff4444;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: none;
        }

        .btn {
            background: #00ff88;
            color: #1a1a2e;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #00ff88;
        }

        .btn-secondary {
            background: #555;
            color: #fff;
        }

        .btn-secondary:hover {
            box-shadow: 0 0 15px #555;
        }

        .start-screen {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(20, 20, 40, 0.98);
            padding: 40px 50px;
            border-radius: 20px;
            border: 3px solid #00ff88;
            text-align: center;
            z-index: 10;
        }

        .start-screen h2 {
            color: #00ff88;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .start-screen p {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #ccc;
        }

        .hidden {
            display: none !important;
        }

        .top10-panel {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(20, 20, 40, 0.98);
            padding: 30px 40px;
            border-radius: 20px;
            border: 3px solid #ff6b35;
            text-align: center;
            z-index: 10;
            max-height: 80vh;
            overflow-y: auto;
            min-width: 350px;
        }

        .top10-panel h2 {
            color: #ff6b35;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .top10-list {
            text-align: left;
            margin-bottom: 20px;
        }

        .top10-list .entry {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            margin: 5px 0;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
        }

        .top10-list .entry.rank-1 {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.3), transparent);
            border: 1px solid #ffd700;
        }

        .top10-list .entry.rank-2 {
            background: linear-gradient(90deg, rgba(192, 192, 192, 0.3), transparent);
            border: 1px solid #c0c0c0;
        }

        .top10-list .entry.rank-3 {
            background: linear-gradient(90deg, rgba(205, 127, 50, 0.3), transparent);
            border: 1px solid #cd7f32;
        }

        .top10-list .rank-num {
            font-weight: bold;
            margin-right: 15px;
            width: 30px;
        }

        .top10-list .rank-name {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .top10-list .rank-score {
            color: #00ff88;
            font-weight: bold;
        }

        .history-btn {
            background: transparent;
            border: 1px solid #ff6b35;
            color: #ff6b35;
            padding: 5px 10px;
            font-size: 0.75rem;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            transition: all 0.3s;
        }

        .history-btn:hover {
            background: #ff6b35;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>SNAKE</h1>
    
    <div class="game-info">
        <div class="score-box">Puntuación: <span id="score">0</span></div>
        <div class="score-box"> Récord: <span id="highScore">0</span></div>
    </div>

    <canvas id="gameCanvas" width="400" height="400"></canvas>

    <div class="controls">
        Usa <kbd>↑</kbd> <kbd>↓</kbd> <kbd>←</kbd> <kbd>→</kbd> para moverte | <kbd>Espacio</kbd> para pausar | <kbd>Top 10</kbd> ver ranking
    </div>

    <div class="start-screen" id="startScreen">
        <h2>¡BIENVENIDO A SNAKE!</h2>
        <p>Come la comida verde para crecer</p>
        <p>¡No choques con las paredes ni contigo mismo!</p>
        <button class="btn" onclick="startGame()">JUGAR</button>
    </div>

    <div class="modal-overlay" id="recordModal">
        <div class="modal">
            <h2>🏆 ¡NUEVO RÉCORD! 🏆</h2>
            <p>Ingresa tu nombre para el ranking:</p>
            <div class="record-score" id="recordScoreDisplay">0</div>
            <input type="text" id="playerName" maxlength="120" placeholder="Tu nombre (máx 120 caracteres)" autocomplete="off">
            <div class="char-count"><span id="charCount">0</span>/120</div>
            <div class="error-msg" id="errorMsg">El nombre no puede estar vacío</div>
            <button class="btn" onclick="saveRecord()">GUARDAR</button>
        </div>
    </div>

    <div class="modal-overlay" id="top10Modal">
        <div class="top10-panel">
            <h2 id="top10Title">🏆 TOP 10 🏆</h2>
            <div class="top10-list" id="top10List"></div>
            <button class="btn btn-secondary" onclick="closeTop10()">CERRAR</button>
            <button class="history-btn" onclick="showHistory()">Ver historial TOP 100</button>
        </div>
    </div>

    <div class="modal-overlay" id="historyModal">
        <div class="top10-panel">
            <h2>🏆 TOP 100 - HISTORIAL COMPLETO 🏆</h2>
            <div class="top10-list" id="historyList"></div>
            <button class="btn btn-secondary" onclick="closeHistory()">CERRAR</button>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        
        const TILE_SIZE = 20;
        const TILE_COUNT = canvas.width / TILE_SIZE;
        
        let snake = [];
        let food = { x: 0, y: 0 };
        let dx = 0;
        let dy = 0;
        let score = 0;
        let gameLoop = null;
        let isPaused = false;
        let isGameOver = false;
        let hasStarted = false;

        function $(id) {
            return document.getElementById(id);
        }

        function getHighScore() {
            try {
                const data = localStorage.getItem('snakeHighScores');
                return data ? JSON.parse(data) : [];
            } catch (e) {
                return [];
            }
        }

        function getTopScore() {
            const scores = getHighScore();
            return scores.length > 0 ? scores[0].score : 0;
        }

        let highScore = getTopScore();
        
        if ($('highScore')) {
            $('highScore').textContent = highScore;
        }

        function initSnake() {
            snake = [
                { x: 10, y: 10 },
                { x: 10, y: 11 },
                { x: 10, y: 12 }
            ];
        }

        function spawnFood() {
            let validPosition = false;
            while (!validPosition) {
                food.x = Math.floor(Math.random() * TILE_COUNT);
                food.y = Math.floor(Math.random() * TILE_COUNT);
                validPosition = !snake.some(segment => segment.x === food.x && segment.y === food.y);
            }
        }

        function draw() {
            ctx.fillStyle = '#0a0a15';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < TILE_COUNT; i++) {
                for (let j = 0; j < TILE_COUNT; j++) {
                    if ((i + j) % 2 === 0) {
                        ctx.fillStyle = 'rgba(255, 255, 255, 0.02)';
                        ctx.fillRect(i * TILE_SIZE, j * TILE_SIZE, TILE_SIZE, TILE_SIZE);
                    }
                }
            }

            ctx.fillStyle = '#ff4444';
            ctx.shadowColor = '#ff4444';
            ctx.shadowBlur = 10;
            ctx.fillRect(food.x * TILE_SIZE + 2, food.y * TILE_SIZE + 2, TILE_SIZE - 4, TILE_SIZE - 4);
            ctx.shadowBlur = 0;

            snake.forEach((segment, index) => {
                const gradient = ctx.createLinearGradient(
                    segment.x * TILE_SIZE, segment.y * TILE_SIZE,
                    (segment.x + 1) * TILE_SIZE, (segment.y + 1) * TILE_SIZE
                );
                
                if (index === 0) {
                    gradient.addColorStop(0, '#00ff88');
                    gradient.addColorStop(1, '#00cc66');
                } else {
                    const alpha = 1 - (index / snake.length) * 0.5;
                    gradient.addColorStop(0, `rgba(0, 255, 136, ${alpha})`);
                    gradient.addColorStop(1, `rgba(0, 200, 100, ${alpha})`);
                }
                
                ctx.fillStyle = gradient;
                ctx.fillRect(segment.x * TILE_SIZE + 1, segment.y * TILE_SIZE + 1, TILE_SIZE - 2, TILE_SIZE - 2);
            });
        }

        function update() {
            if (dx === 0 && dy === 0) return;

            const head = { x: snake[0].x + dx, y: snake[0].y + dy };

            if (head.x < 0 || head.x >= TILE_COUNT || head.y < 0 || head.y >= TILE_COUNT) {
                endGame();
                return;
            }

            if (snake.some(segment => segment.x === head.x && segment.y === head.y)) {
                endGame();
                return;
            }

            snake.unshift(head);

            if (head.x === food.x && head.y === food.y) {
                score += 10;
                if ($('score')) {
                    $('score').textContent = score;
                }
                spawnFood();
            } else {
                snake.pop();
            }
        }

        function gameStep() {
            if (!isPaused && !isGameOver && hasStarted) {
                update();
                draw();
            }
        }

        function startGame() {
            if ($('startScreen')) {
                $('startScreen').classList.add('hidden');
            }
            initSnake();
            spawnFood();
            score = 0;
            dx = 0;
            dy = -1;
            isGameOver = false;
            isPaused = false;
            hasStarted = true;
            if ($('score')) {
                $('score').textContent = score;
            }
            draw();
            
            if (gameLoop) clearInterval(gameLoop);
            gameLoop = setInterval(gameStep, 100);
        }

        function endGame() {
            isGameOver = true;
            if (gameLoop) clearInterval(gameLoop);
            
            if (score > highScore && score > 0) {
                if ($('recordScoreDisplay')) {
                    $('recordScoreDisplay').textContent = score;
                }
                if ($('recordModal')) {
                    $('recordModal').style.display = 'flex';
                }
                if ($('playerName')) {
                    $('playerName').value = '';
                }
                if ($('charCount')) {
                    $('charCount').textContent = '0';
                }
                if ($('errorMsg')) {
                    $('errorMsg').style.display = 'none';
                }
                if ($('playerName')) {
                    $('playerName').focus();
                }
            } else {
                showTop10();
            }
        }

        function saveRecord() {
            const nameInput = $('playerName');
            const errorMsg = $('errorMsg');
            
            if (!nameInput || !errorMsg) return;
            
            const name = nameInput.value.trim();
            
            if (!name) {
                errorMsg.textContent = 'El nombre no puede estar vacío';
                errorMsg.style.display = 'block';
                nameInput.focus();
                return;
            }
            
            if (name.length > 120) {
                errorMsg.textContent = 'El nombre no puede exceder 120 caracteres';
                errorMsg.style.display = 'block';
                return;
            }

            const scores = getHighScore();
            scores.push({
                name: name.substring(0, 120),
                score: score,
                date: new Date().toISOString()
            });
            
            scores.sort((a, b) => b.score - a.score);
            
            const top100 = scores.slice(0, 100);
            localStorage.setItem('snakeHighScores', JSON.stringify(top100));
            
            highScore = score;
            if ($('highScore')) {
                $('highScore').textContent = highScore;
            }
            
            if ($('recordModal')) {
                $('recordModal').style.display = 'none';
            }
            showTop10();
        }

        function showTop10() {
            const scores = getHighScore();
            const top10 = scores.slice(0, 10);
            const container = $('top10List');
            const modal = $('top10Modal');
            const title = $('top10Title');
            
            if (!container || !modal) return;
            
            if (title) {
                title.textContent = '🏆 TOP 10 🏆';
            }
            
            if (top10.length === 0) {
                container.innerHTML = '<p style="text-align:center;color:#888;">No hay puntuaciones aún</p>';
            } else {
                container.innerHTML = top10.map((entry, index) => `
                    <div class="entry rank-${index + 1}">
                        <span class="rank-num">#${index + 1}</span>
                        <span class="rank-name">${escapeHtml(entry.name)}</span>
                        <span class="rank-score">${entry.score}</span>
                    </div>
                `).join('');
            }
            
            modal.style.display = 'flex';
        }

        function showHistory() {
            const scores = getHighScore();
            const top100 = scores.slice(0, 100);
            const container = $('historyList');
            const modal = $('historyModal');
            
            if (!container || !modal) return;
            
            if (top100.length === 0) {
                container.innerHTML = '<p style="text-align:center;color:#888;">No hay puntuaciones aún</p>';
            } else {
                container.innerHTML = top100.map((entry, index) => `
                    <div class="entry rank-${index + 1}">
                        <span class="rank-num">#${index + 1}</span>
                        <span class="rank-name">${escapeHtml(entry.name)}</span>
                        <span class="rank-score">${entry.score}</span>
                    </div>
                `).join('');
            }
            
            if ($('top10Modal')) {
                $('top10Modal').style.display = 'none';
            }
            modal.style.display = 'flex';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function closeTop10() {
            if ($('top10Modal')) {
                $('top10Modal').style.display = 'none';
            }
        }

        function closeHistory() {
            if ($('historyModal')) {
                $('historyModal').style.display = 'none';
            }
        }

        if ($('playerName')) {
            $('playerName').addEventListener('input', function() {
                const len = this.value.length;
                if ($('charCount')) {
                    $('charCount').textContent = len;
                }
                if ($('errorMsg')) {
                    $('errorMsg').style.display = 'none';
                }
            });

            $('playerName').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    saveRecord();
                }
            });
        }

        document.addEventListener('keydown', (e) => {
            const recordModalEl = $('recordModal');
            const top10ModalEl = $('top10Modal');
            const historyModalEl = $('historyModal');
            
            if ((recordModalEl && recordModalEl.style.display === 'flex') || 
                (top10ModalEl && top10ModalEl.style.display === 'flex') ||
                (historyModalEl && historyModalEl.style.display === 'flex')) {
                if (e.code === 'Escape') {
                    closeTop10();
                    closeHistory();
                    if (recordModalEl) recordModalEl.style.display = 'none';
                }
                return;
            }

            if (!hasStarted || isGameOver) {
                if (e.code === 'Space') {
                    startGame();
                }
                if ((e.key === '1' || e.key === 't' || e.key === 'T') && !hasStarted) {
                    showTop10();
                }
                return;
            }

            if (e.code === 'Space') {
                e.preventDefault();
                isPaused = !isPaused;
                return;
            }

            const key = e.key;
            const goingUp = dy === -1;
            const goingDown = dy === 1;
            const goingRight = dx === 1;
            const goingLeft = dx === -1;

            if ((key === 'ArrowUp' || key === 'w') && !goingDown) {
                dx = 0;
                dy = -1;
            } else if ((key === 'ArrowDown' || key === 's') && !goingUp) {
                dx = 0;
                dy = 1;
            } else if ((key === 'ArrowLeft' || key === 'a') && !goingRight) {
                dx = -1;
                dy = 0;
            } else if ((key === 'ArrowRight' || key === 'd') && !goingLeft) {
                dx = 1;
                dy = 0;
            }
        });

        function openTop10() {
            showTop10();
        }

        window.openTop10 = openTop10;

        initSnake();
        spawnFood();
        draw();
    </script>
</body>
</html>

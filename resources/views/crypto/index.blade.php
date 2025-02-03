<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Price Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@pusher/push-notifications-web"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.10.0/dist/echo.iife.js"></script>
    <script src="https://js.pusher.com/7.0.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Light Mode (Default) */
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            font-size: 3rem;
            margin: 40px 0;
            position: relative;
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(45deg, #4CAF50, #2196F3, #4CAF50);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-fill-color: transparent;
            animation: gradient 5s ease infinite;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        h1::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 0%, rgba(255, 255, 255, 0.2) 50%, transparent 100%);
            background-size: 200% auto;
            animation: shimmer 5s ease infinite;
            border-radius: 15px;
            z-index: -1;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Dark mode adjustments */
        body.dark-mode h1 {
            text-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        body.dark-mode h1::after {
            background: linear-gradient(45deg, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
        }

        .crypto-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .crypto-table thead {
            background-color: #007BFF;
            color: white;
        }

        .crypto-table th,
        .crypto-table td {
            padding: 12px 20px;
            text-align: center;
        }

        .crypto-price {
            color: #28a745;
            font-weight: bold;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        .crypto-table.dark-mode thead {
            background-color: #333;
            color: white;
        }

        .crypto-table.dark-mode td,
        .crypto-table.dark-mode th {
            color: #e0e0e0;
        }

        .crypto-price.dark-mode {
            color: #66bb6a;
        }

        /* Stats Card Styles */
        .stats-card {
            margin-bottom: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-title {
            font-size: 1rem;
            font-weight: 600;
            color: #6c757d;
        }

        .stats-value {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .percentage-up {
            color: #28a745;
            background: rgba(40, 167, 69, 0.1);
            padding: 2px 8px;
            border-radius: 4px;
        }

        .trending-list,
        .gainers-list {
            max-height: 200px;
            overflow-y: auto;
        }

        /* Dark Mode Toggle */
        #mode-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 5px 10px;
            border-radius: 20px;
            cursor: pointer;
        }

        .crypto-logo {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        td img {
            vertical-align: middle;
        }

        /* 3D Card Styles */
        .card-3d {
            height: 200px;
            /* Fixed height */
            display: flex;
            flex-direction: column;
        }

        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Grid Layout Adjustment */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        /* Specific Card Adjustments */
        .market-cap-card,
        .volume-card {
            grid-column: span 1;
        }

        .trending-card,
        .gainers-card {
            grid-column: span 1;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Content Alignment */
        .stats-value {
            font-size: 1.5rem;
            margin: 10px 0;
        }

        .card-footer {
            margin-top: auto;
        }

        .card-3d {
            background: #ffffff;
            border-radius: 15px;
            box-shadow:
                0 8px 16px rgba(0, 0, 0, 0.1),
                0 16px 32px rgba(0, 0, 0, 0.1),
                0 32px 64px rgba(0, 0, 0, 0.1);
            transform: perspective(1000px) rotateX(2deg) rotateY(2deg);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg,
                    rgba(255, 255, 255, 0.1) 0%,
                    rgba(255, 255, 255, 0.3) 50%,
                    rgba(255, 255, 255, 0.1) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .card-3d:hover {
            transform: perspective(1000px) rotateX(0) rotateY(0) translateY(-5px);
            box-shadow:
                0 12px 24px rgba(0, 0, 0, 0.15),
                0 24px 48px rgba(0, 0, 0, 0.15);
        }

        /* Different color schemes for each card */
        .market-cap-card {
            background: linear-gradient(135deg, #4CAF50, #2196F3);
        }

        .volume-card {
            background: linear-gradient(135deg, #FF9800, #E91E63);
        }

        .trending-card {
            background: linear-gradient(135deg, #9C27B0, #3F51B5);
        }

        .gainers-card {
            background: linear-gradient(135deg, #009688, #00BCD4);
        }

        /* Dark Mode Adjustments */
        body.dark-mode .card-3d {
            box-shadow:
                0 8px 16px rgba(0, 0, 0, 0.3),
                0 16px 32px rgba(0, 0, 0, 0.3),
                0 32px 64px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .card-3d::before {
            background: linear-gradient(45deg,
                    rgba(0, 0, 0, 0.1) 0%,
                    rgba(0, 0, 0, 0.3) 50%,
                    rgba(0, 0, 0, 0.1) 100%);
        }

        /* Add chart container styling */
        #marketChart {
            height: 400px;
            width: 100%;
        }

        /* Dark mode chart adjustments */
        body.dark-mode .card-3d {
            background: #1e1e1e;
        }

        body.dark-mode .chartjs-render-monitor {
            color: #fff !important;
        }

        .crypto-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .nav-link {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem !important;
        }

        .market-stats-bar {
            font-size: 0.875rem;
        }

        /* Dark mode adjustments */
        body.dark-mode .crypto-header,
        body.dark-mode .market-stats-bar {
            background: #1a1a1a !important;
            border-color: #333 !important;
        }

        body.dark-mode .nav-link,
        body.dark-mode .navbar-brand,
        body.dark-mode .market-stats-bar strong {
            color: #fff !important;
        }

        body.dark-mode .text-muted {
            color: #aaa !important;
        }

        body.dark-mode .vr {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .footer {
            font-size: 0.9rem;
        }

        .footer h5 {
            font-size: 1rem;
            font-weight: 600;
        }

        .footer .bi {
            margin-right: 5px;
        }

        /* Dark mode adjustments */
        body.dark-mode .footer {
            background-color: #1a1a1a !important;
            border-color: #333 !important;
        }

        body.dark-mode .footer .text-muted {
            color: #888 !important;
        }

        body.dark-mode .footer a {
            color: #ccc !important;
        }

        body.dark-mode .footer a:hover {
            color: #fff !important;
        }

        body.dark-mode .btn-dark {
            background-color: #333;
            border-color: #444;
        }

        /* Add social icon hover effects */
        .social-icon {
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-2px);
        }

        .bi-twitter:hover {
            color: #1DA1F2 !important;
        }

        .bi-facebook:hover {
            color: #1877F2 !important;
        }

        .bi-instagram:hover {
            color: #E4405F !important;
        }

        .bi-github:hover {
            color: #181717 !important;
        }

        /* Dark mode adjustments */
        body.dark-mode .social-icon:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .col-md-4.text-md-end {
                text-align: left !important;
            }

            .justify-content-md-end {
                justify-content: flex-start !important;
            }
        }
    </style>
</head>

<body>
    <header class="crypto-header">
        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <a class="navbar-brand fw-bold me-4" href="#">CoinGlide</a>
                    <ul class="nav">
                        <li class="nav-item me-3">
                            <a class="nav-link text-dark" href="#">Personal</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-dark" href="#">Enterprise</a>
                        </li>
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Features
                            </a>
                        </li>
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Learn
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#">Pricing</a>
                        </li>
                    </ul>
                </div>

                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <a href="#" class="text-dark text-decoration-none me-3">Sign in</a>
                    <a href="#" class="btn btn-primary px-3 py-2">Start for free</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Cryptocurrency Price Tracker</h1>
        <button id="mode-toggle" class="btn btn-light">🌙</button>
        <!-- Search Form -->
        <div class="input-group mb-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Search cryptocurrencies...">
            <button class="btn btn-primary" type="button">Search</button>
        </div>

        <!-- Cards Grid -->
        <div class="row g-4 mb-4">
            <!-- Market Cap Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card-3d market-cap-card text-white p-4">
                    <h5 class="stats-title">Market Cap</h5>
                    <div id="marketData"></div>
                </div>
            </div>

            <!-- 24h Volume Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card-3d volume-card text-white p-4">
                    <h5 class="stats-title">24h Volume</h5>
                    <!-- <div class="stats-value display-5 mb-2">$141B</div> -->
                    <div id="marketData"></div>
                </div>
            </div>

            <!-- Trending Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card-3d trending-card text-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="stats-title mb-0">Trending</h5>
                        <!-- <a href="#" class="text-white">View more →</a> -->
                    </div>
                    <div class="trending-list">
                        <!-- Trending items will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Top Gainers Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card-3d gainers-card text-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="stats-title mb-0">Top Gainers</h5>
                        <!-- <a href="#" class="text-white">View more →</a> -->
                    </div>
                    <div class="gainers-list">
                        <!-- Gainers items will be populated here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card-3d p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="stats-title">Market Cap & Volume (7D)</h5>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary active" data-period="7">7D</button>
                            <button class="btn btn-sm btn-outline-secondary" data-period="30">30D</button>
                        </div>
                    </div>
                    <canvas id="marketChart"></canvas>
                </div>
            </div>
        </div>



        <!-- Crypto Data Table -->
        <div class="crypto-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Price (USD)</th>
                        <th>24h Change</th>
                        <th>Market Cap</th>
                    </tr>
                </thead>
                <tbody id="cryptoTableBody">
                    <!-- Filled by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center" id="pagination">
                <!-- Filled by JavaScript -->
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_KEY = 'CG-PPGDkVdBctELev3X8oKn5iBm';
        const API_BASE = 'https://api.coingecko.com/api/v3';
        let currentPage = 1;
        const itemsPerPage = 10;
        let allCoinsData = [];
        let filteredCoinsData = [];


        function populateTable(data) {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedData = data.slice(start, end);

            document.getElementById('cryptoTableBody').innerHTML = paginatedData.map(coin => `
            <tr>
                <td><img src="${coin.image}" alt="${coin.name}" style="width: 24px; height: 24px"></td>
                <td>${coin.name}</td>
                <td>${coin.symbol.toUpperCase()}</td>
                <td class="crypto-price">$${coin.current_price.toFixed(2)}</td>
                <td class="${coin.price_change_percentage_24h >= 0 ? 'text-success' : 'text-danger'}">
                    ${coin.price_change_percentage_24h.toFixed(2)}%
                </td>
                <td>$${coin.market_cap.toLocaleString()}</td>
            </tr>
        `).join('');
        }
        // Dark Mode Toggle
        const modeToggle = document.getElementById('mode-toggle');
        modeToggle.addEventListener('click', toggleDarkMode);

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
            modeToggle.textContent = document.body.classList.contains('dark-mode') ? '🌞' : '🌙';
        }

        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            modeToggle.textContent = '🌞';
        }

        // Fetch and display data
        async function fetchData() {
            try {
                const [globalRes, trendingRes, gainersRes, coinsRes] = await Promise.all([
                    fetch(`${API_BASE}/global`),
                    fetch(`${API_BASE}/search/trending`),
                    fetch(`${API_BASE}/coins/markets?vs_currency=usd&order=percent_change_24h_desc&per_page=5`),
                    fetch(`${API_BASE}/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=250`)
                ]);

                const [globalData, trendingData, gainersData, coinsData] = await Promise.all([
                    globalRes.json(),
                    trendingRes.json(),
                    gainersRes.json(),
                    coinsRes.json()
                ]);

                allCoinsData = coinsData;
                filteredCoinsData = [...allCoinsData]; // Initial filtered data is all coins

                updateMarketData(globalData);
                updateTrending(trendingData);
                updateGainers(gainersData);
                populateTable(filteredCoinsData);
                setupPagination(filteredCoinsData);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        function updateMarketData(data) {
            document.getElementById('marketData').innerHTML = `
            <h5 class="stats-title">Market Cap</h5>
            <div class="stats-value">$${data.data.total_market_cap.usd.toLocaleString()}</div>
            <small class="text-muted">${data.data.market_cap_change_percentage_24h_usd.toFixed(1)}%</small>
            
            <h5 class="stats-title mt-4">24h Volume</h5>
            <div class="stats-value">$${data.data.total_volume.usd.toLocaleString()}</div>
        `;
        }

        function updateTrending(data) {
            document.getElementById('trendingList').innerHTML = data.coins.slice(0, 5).map(coin => `
            <div class="d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong>${coin.item.name}</strong>
                    <div class="text-muted small">$${coin.item.data.price.toFixed(6)}</div>
                </div>
                <span class="percentage-up">+${coin.item.data.price_change_percentage_24h.usd.toFixed(1)}%</span>
            </div>
        `).join('');
        }

        function updateGainers(data) {
            document.getElementById('gainersList').innerHTML = data.map(coin => `
            <div class="d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong>${coin.name}</strong>
                    <div class="text-muted small">$${coin.current_price.toFixed(6)}</div>
                </div>
                <span class="percentage-up">+${coin.price_change_percentage_24h.toFixed(1)}%</span>
            </div>
        `).join('');
        }

        function populateTable(data) {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedData = data.slice(start, end);

            document.getElementById('cryptoTableBody').innerHTML = paginatedData.map(coin => `
            <tr>
                <td><img src="${coin.image}" alt="${coin.name}" style="width: 24px; height: 24px"></td>
                <td>${coin.name}</td>
                <td>${coin.symbol.toUpperCase()}</td>
                <td class="crypto-price">$${coin.current_price.toFixed(2)}</td>
                <td class="${coin.price_change_percentage_24h >= 0 ? 'text-success' : 'text-danger'}">
                    ${coin.price_change_percentage_24h.toFixed(2)}%
                </td>
                <td>$${coin.market_cap.toLocaleString()}</td>
            </tr>
        `).join('');
        }

        function setupPagination(data) {
            const totalPages = Math.ceil(data.length / itemsPerPage);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    pagination.innerHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
                }
            }

            // Update pagination event listeners
            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = parseInt(e.target.dataset.page);
                    populateTable(filteredCoinsData);
                    updatePaginationStyles();
                });
            });

        }

        function updatePaginationStyles() {
            document.querySelectorAll('.page-item').forEach(item => {
                item.classList.remove('active');
                if (parseInt(item.querySelector('.page-link').textContent) === currentPage) {
                    item.classList.add('active');
                }
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filteredCoinsData = allCoinsData.filter(coin => {
                return coin.name.toLowerCase().includes(searchTerm) ||
                    coin.symbol.toLowerCase().includes(searchTerm);
            });

            currentPage = 1; // Reset to first page
            populateTable(filteredCoinsData);
            setupPagination(filteredCoinsData);
        });

        // Initial load
        fetchData();
        setInterval(fetchData, 120000);
    </script>

    <script>
        // Update the updateMarketData function
        function updateMarketData(data) {
            document.querySelector('.market-cap-card').innerHTML = `
            <h5 class="stats-title">Market Cap</h5>
            <div class="stats-value display-5 mb-2">$${(data.data.total_market_cap.usd / 1e9).toFixed(1)}B</div>
            <div class="text-white-50">${data.data.market_cap_change_percentage_24h_usd.toFixed(1)}%</div>
        `;

            document.querySelector('.volume-card').innerHTML = `
            <h5 class="stats-title">24h Volume</h5>
            <div class="stats-value display-5 mb-2">$${(data.data.total_volume.usd / 1e9).toFixed(1)}B</div>
            <div class="text-white-50">${data.data.market_cap_change_percentage_24h_usd.toFixed(1)}%</div>
        `;
        }

        // Update the updateTrending and updateGainers functions
        function updateTrending(data) {
            document.querySelector('.trending-list').innerHTML = data.coins.slice(0, 3).map(coin => `
            <div class="d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong>${coin.item.name}</strong>
                    <div class="small opacity-75">$${coin.item.data.price.toFixed(6)}</div>
                </div>
                <span class="badge bg-white text-dark">+${coin.item.data.price_change_percentage_24h.usd.toFixed(1)}%</span>
            </div>
        `).join('');
        }

        function updateGainers(data) {
            document.querySelector('.gainers-list').innerHTML = data.map(coin => `
            <div class="d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong>${coin.name}</strong>
                    <div class="small opacity-75">$${coin.current_price.toFixed(6)}</div>
                </div>
                <span class="badge bg-white text-dark">+${coin.price_change_percentage_24h.toFixed(1)}%</span>
            </div>
        `).join('');
        }
    </script>


    <script>
        // Add these variables at the top
        let marketChart;
        const chartColors = {
            marketCap: '#4CAF50',
            volume: '#2196F3'
        };

        // Add this function to fetch chart data
        async function fetchChartData(days = 7) {
            try {
                const response = await fetch(
                    `${API_BASE}/coins/bitcoin/market_chart?vs_currency=usd&days=${days}&interval=daily`);
                const data = await response.json();
                return {
                    labels: data.market_caps.map((_, index) => `Day ${index + 1}`),
                    marketCaps: data.market_caps.map(mc => mc[1]),
                    volumes: data.total_volumes.map(vol => vol[1])
                };
            } catch (error) {
                console.error('Error fetching chart data:', error);
                return null;
            }
        }

        // Add this function to initialize/update the chart
        async function updateChart(days = 7) {
            const chartData = await fetchChartData(days);
            if (!chartData) return;

            const ctx = document.getElementById('marketChart').getContext('2d');

            if (marketChart) {
                marketChart.destroy();
            }

            marketChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Market Cap',
                            data: chartData.marketCaps,
                            borderColor: chartColors.marketCap,
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: '24h Volume',
                            data: chartData.volumes,
                            borderColor: chartColors.volume,
                            backgroundColor: 'rgba(33, 150, 243, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Market Cap (USD)'
                            },
                            grid: {
                                color: ctx => document.body.classList.contains('dark-mode') ?
                                    'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Volume (USD)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            grid: {
                                color: ctx => document.body.classList.contains('dark-mode') ?
                                    'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: ctx => document.body.classList.contains('dark-mode') ? '#fff' : '#666'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: ctx => document.body.classList.contains('dark-mode') ?
                                'rgba(0,0,0,0.9)' : 'rgba(255,255,255,0.9)',
                            titleColor: ctx => document.body.classList.contains('dark-mode') ? '#fff' : '#666',
                            bodyColor: ctx => document.body.classList.contains('dark-mode') ? '#fff' : '#666'
                        }
                    }
                }
            });
        }

        // Add time period buttons functionality
        document.querySelectorAll('[data-period]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('[data-period]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                updateChart(parseInt(this.dataset.period));
            });
        });

        // Add to your existing updateAllData function
        async function updateAllData() {
            await fetchData();
            await updateChart(); // Add this line
        }

        // Add dark mode toggle handler
        function toggleDarkMode() {
            // ... existing dark mode code ...
            if (marketChart) {
                marketChart.update();
            }
        }

        // Initialize chart on first load
        updateChart();
    </script>

    <footer class="footer mt-auto py-4 bg-light border-top">
        <div class="container">
            <!-- Footer Top Section -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <h5 class="mb-3">Download our app</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-dark btn-sm">
                            <i class="bi bi-apple"></i> App Store
                        </a>
                        <a href="#" class="btn btn-dark btn-sm">
                            <i class="bi bi-google-play"></i> Google Play
                        </a>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <h5 class="mb-3">CoinGlide</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="#" class="text-decoration-none text-muted">About Us</a>
                        <a href="#" class="text-decoration-none text-muted">Blog</a>
                        <a href="#" class="text-decoration-none text-muted">Careers</a>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <h5 class="mb-3">Photo/Help</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="#" class="text-decoration-none text-muted">Support Center</a>
                        <a href="#" class="text-decoration-none text-muted">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-md-end">
                <h5 class="mb-3">Connect With Us</h5>
                <div class="d-flex justify-content-md-end gap-3">
                    <a href="#" class="text-decoration-none text-muted social-icon">
                        <i class="bi bi-twitter fs-5"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-muted social-icon">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-muted social-icon">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-muted social-icon">
                        <i class="bi bi-github fs-5"></i>
                    </a>
                </div>
            </div>


            <!-- Footer Bottom Section -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 border-top">
                <div class="mb-2 mb-md-0">
                    <span class="text-muted small">© CoinGlide 2025</span>
                </div>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none text-muted small">Home</a>
                    <a href="#" class="text-decoration-none text-muted small">Disclaimer</a>
                    <a href="#" class="text-decoration-none text-muted small">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted small">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
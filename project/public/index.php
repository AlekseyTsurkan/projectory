<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск комментариев</title>
    <style>
      
        .search-container { max-width: 800px; margin: 2rem auto; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .search-box { display: grid; grid-template-columns: 1fr auto; gap: 10px; }
        input { padding: 10px; border: 2px solid #007bff; border-radius: 4px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; }
        .result-item { margin: 15px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="search-container">
        <h1>Поиск по комментариям</h1>
        <form id="searchForm">
            <div class="search-box">
                <input 
                    type="search" 
                    name="query" 
                    placeholder="Введите фразу для поиска..."
                    minlength="3"
                    required
                >
                <button type="submit">Найти</button>
            </div>
        </form>
        <div id="results"></div>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const query = formData.get('query').trim();
            
            if(query.length < 3) return;
            
            const response = await fetch(`search.php?query=${encodeURIComponent(query)}`);
            const results = await response.json();
            
            const resultsContainer = document.getElementById('results');
            resultsContainer.innerHTML = results.length ? 
                results.map(r => `
                    <div class="result-item">
                        <h3>${r.title}</h3>
                        <div>${r.content}</div>
                    </div>
                `).join('') : 
                '<p>Совпадений не найдено</p>';
        });
    </script>
</body>
</html>
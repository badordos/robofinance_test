Тестовое задания для 
<a href='https://github.com/RoboFinance/test-assignments/blob/main/tasks/php_dev_assignment.md'>Robofinance</a>


От корня проекта
<p><code>cd laradock</code></p>
Поднимаем докер
<p><code>docker-compose up -d nginx mysql redis workspace</code></p>
Входим в рабочее пространство
<p><code>docker-compose exec workspace bash</code></p>
Копируем .env файл 
<p><code>cp env-example .env</code></p>
Устанавливаем вендоры
<p><code>composer install</code></p>
Устанавливаем ui на bootstrap
<p><code>php artisan ui bootstrap --auth</code></p>
Собираем фронт
<p><code>npm install</code></p>
<p><code>npm update</code></p>
<p><code>npm run dev</code></p>
Заполняем базу
<p><code>php artisan migrate --seed</code></p>
Включаем слушатель очереди
<p><code>php artisan queue:listen</code></p>

Если окружение стоит в <code>APP_ENV=local</code> - переводы будут для теста всегда выполнятся через 10 секунд

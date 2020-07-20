Installation
---

Зависимости: make, git, docker, docker-compose

```shell script
git clone https://github.com/buyanov/tk-team-test-api.git

// смотрим допустимые команды
make help

// собираем образ
make build

// ставим зависимости локально
make install

// запускаем приложение
make up

// накатываем тестовые данные и поисковый индекс
make init

// проверяем codestyle
make cs

// запускаем тесты
make test

// во время работы можно посмотреть логи
make logs

// останавливаем и удаляем контейнеры
make down
```

В качестве веб сервера и менеджера процессов используется RoadRunner
запущен он с APP_REFRESH=true конфиг лежит ./.rr.yaml

вместо привычных ```dd()``` и ```dump()``` надо использовать ```\dev\dd()``` и ```\dev\dump()```

В проекте не делал кеширования, думаю на начальном этапе это бессмысленная трата времени

Индексация новых тасков делается через очереди
(иногда при старте индекс может быть пустым, тогда надо просто переиндексировать данные из базы)
```shell script
docker-compose exec app php artisan scout:import "App\Task"
```

Docs
---

https://documenter.getpostman.com/view/2790977/T1DjkKyg?version=latest

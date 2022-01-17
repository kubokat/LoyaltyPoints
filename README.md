# Loyalty Service

## Вводная информация

В репозитории находится реализация микро-сервиса, который отвечает за работу 
системы лояльности и может использоваться в розничной торговле или сфере услуг,
везде, где за какую либо операцию (продажу) можно начислить определённое 
количество баллов лояльности, а потом потратить эти баллы на оплату товаров/услуг.

Доступные операции:
- Начисление баллов лояльности за операцию (loyaltyPoints/deposit).
- Отмена начисления баллов за операцию (loyaltyPoints/cancel).
- Оплата покупки баллами лояльности (loyaltyPoints/withdraw).
- Получение текущего баланса по карте лояльности (накопленное количество баллов лояльности) (account/balance).

```bash
cd test-task-loyalty-service; docker-compose up
docker run -it --user www -v ${pwd}:/var/www test-task-loyalty-service /bin/sh -lc "composer install && cp .env.example .env && php artisan key:generate && php artisan migrate"
```

- Linux
```bash
cd test-task-loyalty-service && docker-compose up
docker run -it --user www -v $PWD:/var/www test-task-loyalty-service /bin/sh -lc "composer install && cp .env.example .env && php artisan key:generate && php artisan migrate"
```

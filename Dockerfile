FROM php:8.3-cli

WORKDIR /var/www/bot
CMD ["php", "./Bot.php"]
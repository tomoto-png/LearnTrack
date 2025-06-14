#!/bin/bash

# cron 起動
cron

# Laravel サーバー起動（ポートとホストを明示）
php artisan serve --host=0.0.0.0 --port=8000
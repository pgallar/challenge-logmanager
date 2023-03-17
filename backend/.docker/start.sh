#! /bin/bash
service supervisor start &
php /app/artisan serve --host=0.0.0.0 --port=8000
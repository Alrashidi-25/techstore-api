#!/bin/bash
echo "=== TechStore Azure Deployment ==="

cd /home/site/wwwroot

echo "Running composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

echo "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "=== Deployment Complete ==="
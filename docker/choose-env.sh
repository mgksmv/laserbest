#!/usr/bin/env bash
read -p "🌿 Выбери окружение [dev/ip/prod] (по умолчанию dev): " env
if [ -z "$env" ] || [ "$env" = "dev" ]; then
  echo docker-compose.yml
elif [ "$env" = "ip" ]; then
  echo docker-compose.ip.yml
elif [ "$env" = "prod" ]; then
  echo docker-compose.prod.yml
else
  echo docker-compose.yml
fi

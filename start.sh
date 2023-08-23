#!/bin/bash

# Запуск контейнера ngrok
docker-compose up -d ngrok

#!/bin/sh

# Load variables from .env file
source .env

# Set local port from command line arg or default to 80
LOCAL_PORT=$LOCAL_PORT

echo "Start ngrok in background on port [ $LOCAL_PORT ]"
nohup ngrok http "${LOCAL_PORT}" &>/dev/null &

echo -n "Extracting ngrok public url ."
HOOK_URL=""
while [ -z "$HOOK_URL" ]; do
  # Run 'curl' against ngrok API and extract public (using 'sed' command)
  export HOOK_URL=$(curl --silent --max-time 10 --connect-timeout 5 \
                            --show-error http://127.0.0.1:4040/api/tunnels | \
                            sed -nE 's/.*public_url":"https:..([^"]*).*/\1/p')
  sleep 1
  echo -n "."
done
# Check if HOOK_URL exists in .env.local file
if grep -q "HOOK_URL" .env.local; then
  # If HOOK_URL exists, update its value
  sed -i "s/HOOK_URL=.*/HOOK_URL=$HOOK_URL/g" .env.local
else
  # If HOOK_URL does not exist, add it to .env.local file
  echo "HOOK_URL=$HOOK_URL" >> .env.local
fi
echo -e "HOOK_URL => [ $HOOK_URL ]"
echo -e

response=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:4040/inspect/http)

if [ "$response" -eq 200 ] ; then
  echo "Статусный код: $response"
  echo "Ngrok работает нормально"
else
  docker-compose down
  echo "Ngrok не запустился"
fi

docker-compose up -d

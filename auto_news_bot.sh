#!/bin/bash

echo "Starting Auto News Bot..."
echo "This bot will fetch and post trending tech news using Gemini at the configured interval."
echo "Press Ctrl+C to stop."

while true; do
  # Get config from local backend api
  CONFIG=$(curl -s http://localhost:8000/api/news/auto-update-config)
  
  ENABLED=$(echo "$CONFIG" | grep -o '"enabled":[^,]*' | cut -d: -f2 | tr -d ' "')
  INTERVAL=$(echo "$CONFIG" | grep -o '"interval":[^,]*' | cut -d: -f2 | tr -d ' "}')
  
  if [ -z "$INTERVAL" ] || [ "$INTERVAL" -lt 1 ]; then
    INTERVAL=10 # fallback
  fi

  if [ "$ENABLED" = "true" ]; then
    echo "[$(date)] Auto news update is enabled. Fetching new article..."
    RESPONSE=$(curl -s -X POST http://localhost:8000/api/news/auto-generate)
    echo "[$(date)] Response: $RESPONSE"
  else
    echo "[$(date)] Auto news update is disabled in settings. Skipping..."
  fi
  
  SLEEP_SECS=$((INTERVAL * 60))
  echo "[$(date)] Waiting $INTERVAL minutes ($SLEEP_SECS seconds) before next check..."
  sleep $SLEEP_SECS
done

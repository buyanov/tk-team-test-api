#!/usr/bin/env sh
set -e

APP_DIR="${APP_DIR:-/app}";
STARTUP_DELAY="${STARTUP_DELAY:-0}";
STARTUP_WAIT_FOR_SERVICES="${STARTUP_WAIT_FOR_SERVICES:-false}";
STARTUP_SETUP_RABBIT="${STARTUP_SETUP_RABBIT:-false}";

if [ "$STARTUP_DELAY" -gt 0 ]; then
  echo "[INFO] Wait $STARTUP_DELAY seconds before start ..";
  sleep "$STARTUP_DELAY";
fi;

if ! php "${APP_DIR}/artisan" --version > /dev/null 2>&1; then
  (>&2 echo "[WARNING] Application probably broken down!");
fi;

exec "$@";

#!/bin/bash
# Runs the dev server (`npm run dev`) for the vite service in compose.yaml.
set -e

# Reinstall node_modules when package-lock.json changed since the last install,
# or when they came from Windows npm (Windows-only binaries can't run here).
lock_hash=$(sha1sum package-lock.json | cut -d' ' -f1)
if [ ! -d node_modules/@rollup/rollup-linux-x64-gnu ] \
    || [ "$(cat node_modules/.installed-lock-hash 2>/dev/null)" != "$lock_hash" ]; then
    echo "Installing npm packages (npm ci)..."
    npm ci --no-audit --no-fund
    echo "$lock_hash" > node_modules/.installed-lock-hash
fi

# Same as `npm run dev` (package.json runs "vite"), but started directly so
# Vite itself gets Docker's stop signal and deletes public/hot on the way out.
# Through npm the signal never reached it, and the leftover public/hot sent
# pages looking for a dev server that was gone (no CSS).
exec node_modules/.bin/vite

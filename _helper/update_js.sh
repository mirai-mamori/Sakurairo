#!/usr/bin/env bash
set -euo pipefail

SCRIPT_PATH="${BASH_SOURCE[0]:-$0}"
SCRIPT_DIR="$(cd "$(dirname "$SCRIPT_PATH")" && pwd -P)"
ROOT_DIR="$(cd "$SCRIPT_DIR/.." && pwd -P)"
SCRIPTS_DIR="$ROOT_DIR/attached/Sakurairo_Scripts"
DIST_DIR="$SCRIPTS_DIR/dist"
THEME_JS_DIR="$ROOT_DIR/js"

usage() {
    cat <<'EOF'
Usage: ./_helper/update_js.sh [--install]

Build JS assets from attached/Sakurairo_Scripts and sync to ./js.

Options:
  --install   Run yarn install before build
  -h, --help  Show this help
EOF
}

INSTALL_DEPS=0
for arg in "$@"; do
    case "$arg" in
        --install)
            INSTALL_DEPS=1
            ;;
        -h|--help)
            usage
            exit 0
            ;;
        *)
            echo "Unknown argument: $arg" >&2
            usage
            exit 1
            ;;
    esac
done

if ! command -v yarn >/dev/null 2>&1; then
    echo "yarn is required but not found in PATH" >&2
    exit 1
fi

if [[ ! -d "$SCRIPTS_DIR" ]]; then
    echo "Scripts directory not found: $SCRIPTS_DIR" >&2
    exit 1
fi

echo "==> Building assets in $SCRIPTS_DIR"
cd "$SCRIPTS_DIR"

if [[ "$INSTALL_DEPS" -eq 1 ]]; then
    echo "==> Installing dependencies"
    yarn install
fi

yarn build

if [[ ! -d "$DIST_DIR" ]]; then
    echo "Build output not found: $DIST_DIR" >&2
    exit 1
fi

echo "==> Syncing $DIST_DIR -> $THEME_JS_DIR"
mkdir -p "$THEME_JS_DIR"

echo "==> Cleaning $THEME_JS_DIR"
rm -rf "$THEME_JS_DIR"/*
cp -a "$DIST_DIR"/. "$THEME_JS_DIR"/

echo "==> Done"

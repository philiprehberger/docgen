#!/usr/bin/env bash
# Build downloadable SDK zip archives for the docs site Downloads page.
# Writes to public/downloads/ so the docs site can link directly.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT="${ROOT}/public/downloads/sdks"
mkdir -p "${OUT}"

for lang in typescript php python; do
    src="${ROOT}/sdks/${lang}"
    if [ ! -d "${src}" ]; then
        echo "Skipping ${lang}: no SDK directory"
        continue
    fi

    zipfile="${OUT}/docgen-${lang}-sdk.zip"
    rm -f "${zipfile}"

    (cd "${ROOT}/sdks" && zip -qr "${zipfile}" "${lang}" -x "**/node_modules/*" "**/vendor/*" "**/__pycache__/*")

    echo "  wrote ${zipfile} ($(wc -c < "${zipfile}") bytes)"
done

echo
echo "Done. Zips at ${OUT}/"

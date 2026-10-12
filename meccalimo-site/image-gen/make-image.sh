#!/bin/bash
# Make a realistic Mecca Limo image from a fleet reference photo via Codex (ChatGPT).
# Usage: make-image.sh <vehicle: sprinter|navigator|suburban|sedan> <out-name> <WxH> "<scene>"
set -e
DIR="$(cd "$(dirname "$0")" && pwd)"
VEH="$1"; NAME="$2"; SIZE="$3"; SCENE="$4"
REF="$DIR/refs/$VEH.png"
[ -f "$REF" ] || { echo "no reference for $VEH"; exit 1; }
mkdir -p "$DIR/out"

read -r -d '' PROMPT <<EOF || true
Use your image generation tool to edit/extend the attached reference photo. Save exactly one file: out/$NAME.png at $SIZE pixels.

KEEP THE VEHICLE IDENTICAL to the reference: same make, model, generation, body shape, grille, headlights, wheels, trim, window tint and gloss black paint. Do not redesign, restyle or invent a different vehicle. Real-world proportions and correct number of doors and wheels.

NEW SCENE: $SCENE

PHOTO STYLE: professional automotive photograph, full-frame camera, 35mm lens at eye level, natural light, realistic reflections and shadows that match the scene's light direction, sharp focus on the vehicle, true-to-life colors, subtle depth of field. The vehicle sits naturally on the ground with correct contact shadows.

DO NOT ADD: any text, letters, signs with words, logos, license plate text, watermarks, people's faces, extra vehicles, stretch limousines, party buses, cartoon or CGI look, warped wheels, melted details, impossible reflections.

Only create that one file, nothing else.
EOF

cd "$DIR"
printf "%s" "$PROMPT" | codex exec -C "$DIR" -s workspace-write --skip-git-repo-check -i "$REF" > "out/$NAME.log" 2>&1
ls -la "out/$NAME.png"

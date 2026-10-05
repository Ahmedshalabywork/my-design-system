#!/bin/bash
# Reinstall the Novamira CLI (WordPress connection for meccalimo.com) in Claude Code cloud sessions.
# SEO skills live in .claude/skills/ and plugins are declared in .claude/settings.json,
# so Claude Code loads those on its own.
set -euo pipefail

if [ "${CLAUDE_CODE_REMOTE:-}" != "true" ]; then
  exit 0
fi

if command -v novamira >/dev/null 2>&1 && [ -f "$HOME/.claude/skills/novamira/SKILL.md" ]; then
  exit 0
fi

curl -fsSL https://raw.githubusercontent.com/use-novamira/novamira-cli/main/install.sh \
  | env NOVAMIRA_AGENT='claude-code' sh >/dev/null

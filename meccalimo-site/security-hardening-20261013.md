# Security pass, Oct 13 2026
- Deleted inactive wp-file-manager plugin.
- Moved quote-form backup out of public plugins folder (now ~/mecca-backups/).
- Removed readme.html (version leak).
- Revoked unused application passwords "zapier" and "Claude" (never used).
- novamira-sandbox/mecca-hardening.php: DISALLOW_FILE_EDIT, xmlrpc off, no X-Pingback.
- .htaccess: deny xmlrpc.php (backup at ~/mecca-backups/htaccess-20261013.bak).
- Cleared WP Super Cache: visitors were seeing the old quote form.
- Oct 14: fleet-sprinter-wide/fleet-suv recompressed (172->71KB, 126->52KB); party bus wording removed from mecca-home templates/schema/llms.txt (backups in ~/mecca-backups).

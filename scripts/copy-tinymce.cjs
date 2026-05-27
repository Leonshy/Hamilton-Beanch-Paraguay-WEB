const fs = require('fs');
const path = require('path');

const src = path.join(__dirname, '..', 'node_modules', 'tinymce');
const dest = path.join(__dirname, '..', 'public', 'tinymce');

if (fs.existsSync(src)) {
    fs.cpSync(src, dest, { recursive: true });
    console.log('TinyMCE copied to public/tinymce');
} else {
    console.error('tinymce not found in node_modules — run pnpm install first');
    process.exit(1);
}

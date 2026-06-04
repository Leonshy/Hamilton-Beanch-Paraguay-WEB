const fs = require('fs');
const path = require('path');

const src = path.join(__dirname, '..', 'node_modules', 'tinymce');
const dest = path.join(__dirname, '..', 'public', 'tinymce');

if (!fs.existsSync(src)) {
    console.error('tinymce not found in node_modules — run pnpm install first');
    process.exit(1);
}

if (fs.existsSync(dest)) {
    fs.rmSync(dest, { recursive: true, force: true });
}

fs.cpSync(src, dest, { recursive: true, dereference: true });
console.log('TinyMCE copied to public/tinymce');

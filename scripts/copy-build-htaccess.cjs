const fs = require('fs');
const path = require('path');

// public/build está en .gitignore y se regenera en cada `vite build` —
// este .htaccess (cache agresivo para los assets con hash en el nombre)
// tiene que copiarse de nuevo cada vez, igual que TinyMCE.
const src = path.join(__dirname, 'build.htaccess');
const dest = path.join(__dirname, '..', 'public', 'build', '.htaccess');

if (!fs.existsSync(path.dirname(dest))) {
    console.error('public/build no existe — correr "vite build" primero');
    process.exit(1);
}

fs.copyFileSync(src, dest);
console.log('.htaccess copiado a public/build');

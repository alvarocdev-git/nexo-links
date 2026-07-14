// Generates favicons, touch icons, the web manifest icons and the Open
// Graph image from resources/brand/mark.svg.
// Run after changing the mark: node scripts/generate-brand-assets.mjs
import sharp from 'sharp';
import pngToIco from 'png-to-ico';
import { readFileSync, writeFileSync, copyFileSync } from 'node:fs';

const mark = readFileSync('resources/brand/mark.svg');

// Favicon + app icons (transparent background)
const sizes = {
    'public/favicon-16.png': 16,
    'public/favicon-32.png': 32,
    'public/apple-touch-icon.png': 180,
    'public/icon-192.png': 192,
    'public/icon-512.png': 512,
};

for (const [file, size] of Object.entries(sizes)) {
    await sharp(mark).resize(size, size).png().toFile(file);
    console.log(`✓ ${file}`);
}

// Explicit sizes keep the ico small (the default embeds a 256px layer).
writeFileSync('public/favicon.ico', await pngToIco(['public/favicon-16.png', 'public/favicon-32.png']));
console.log('✓ public/favicon.ico');

copyFileSync('resources/brand/mark.svg', 'public/favicon.svg');
console.log('✓ public/favicon.svg');

// Open Graph card (1200x630)
const og = `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630">
    <defs>
        <linearGradient id="g" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
            <stop stop-color="#6366f1"/>
            <stop offset="1" stop-color="#d946ef"/>
        </linearGradient>
    </defs>
    <rect width="1200" height="630" fill="#0a0a0a"/>
    <g transform="translate(80 155) scale(2.8) rotate(-45 32 32)" fill="none" stroke="url(#g)" stroke-width="7">
        <rect x="4" y="24" width="30" height="16" rx="8"/>
        <rect x="30" y="24" width="30" height="16" rx="8"/>
    </g>
    <text x="330" y="300" font-family="Helvetica, Arial, sans-serif" font-size="96" font-weight="700" fill="#fafafa">Nexo Links</text>
    <text x="332" y="380" font-family="Helvetica, Arial, sans-serif" font-size="40" fill="#a3a3a3">Your links. Your domain. Your data.</text>
</svg>`;

await sharp(Buffer.from(og)).png().toFile('public/og-image.png');
console.log('✓ public/og-image.png');

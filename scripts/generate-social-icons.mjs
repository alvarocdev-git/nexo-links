// Generates Blade icon components from simple-icons (brand SVGs, MIT).
// Run after changing the platform list: node scripts/generate-social-icons.mjs
import * as icons from 'simple-icons';
import { mkdirSync, writeFileSync } from 'node:fs';

const platforms = {
    instagram: icons.siInstagram,
    x: icons.siX,
    tiktok: icons.siTiktok,
    youtube: icons.siYoutube,
    github: icons.siGithub,
    twitch: icons.siTwitch,
    facebook: icons.siFacebook,
    telegram: icons.siTelegram,
    whatsapp: icons.siWhatsapp,
};

const dir = 'resources/views/components/icons';
mkdirSync(dir, { recursive: true });

for (const [slug, icon] of Object.entries(platforms)) {
    const blade = `<svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="${icon.path}"/></svg>\n`;
    writeFileSync(`${dir}/${slug}.blade.php`, blade);
    console.log(`✓ ${slug}`);
}

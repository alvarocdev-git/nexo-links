<?php

// Help center FAQs for Nexo Links (rendered by HelpController). Answers may
// contain inline HTML; they are printed with {!! !!} in help/index.blade.php.
return [
    'faqs' => [
        [
            'q' => 'How do I create my page?',
            'a' => 'Click "Create your page", choose a username — it becomes your public URL at /your-username — and verify your email with the link we send you. Your page is live immediately.',
        ],
        [
            'q' => 'How do I add and reorder my links?',
            'a' => 'In the dashboard, press "+ Add link" and give it a title and a URL. Drag the handle on the left of each card to reorder, and use Hide to keep a link without showing it or Edit to change it anytime.',
        ],
        [
            'q' => 'Can I schedule links, highlight them or add a countdown?',
            'a' => 'Yes. When creating or editing a link you can set optional start and end dates so it publishes and unpublishes itself, mark "Highlight this link" for the accent treatment, or enable a countdown so visitors see a live timer until it starts.',
        ],
        [
            'q' => 'Can a link send an email, call me or open WhatsApp?',
            'a' => 'Links accept https://, mailto: and tel:, so a button can email or call you. Use "Build a WhatsApp link" to generate a wa.me link with a prefilled message.',
        ],
        [
            'q' => 'How do the social icons work?',
            'a' => 'In "Social icons", pick a platform and enter your handle, email or phone. They show as icons at the bottom of your page. Prefer a big button instead? Add it as a regular link.',
        ],
        [
            'q' => 'How do I customize the look of my page?',
            'a' => 'In Design, upload an avatar and a banner, write your bio, and pick an accent palette and a background: default, solid color or gradient. Text color adapts automatically so your page stays readable.',
        ],
        [
            'q' => 'What do the analytics show, and do they track visitors?',
            'a' => 'Analytics shows total clicks, unique visitors, clicks per day and your top referrers. Numbers are collected without cookies and without storing personal data — visitors are never tracked across days.',
        ],
        [
            'q' => 'How do I share my page?',
            'a' => 'Copy your URL from "Share your page" in the dashboard, or download your QR code as SVG and print it anywhere — it always points to your page.',
        ],
    ],
];

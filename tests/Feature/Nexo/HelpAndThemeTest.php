<?php

// Guardian: the help center is served and translatable, and the theme-init +
// tokens are wired into the shell (so light/dark works everywhere). The shell
// route here is the landing ('/').

it('serves a translatable help center', function () {
    $this->get('/help')
        ->assertOk()
        ->assertSee(__('nexo.help.title'));
});

it('stamps the theme before paint and ships the tokens (light/dark ready)', function () {
    $html = $this->get('/')->assertOk()->getContent();

    // The FOUC-free theme init sets <html data-theme> ...
    expect($html)->toContain('data-theme');
    // ... and the brand layer is wired into the shell: either the token stylesheet
    // (inline --nexo-*, the source app.css, or the compiled Vite build link) or the
    // token-styled chrome (nexo-header/nexo-footer), so it holds with or without a
    // built frontend.
    expect($html)->toMatch('#--nexo-|nexo-brand|tokens\.css|app\.css|/build/assets/app-|nexo-header|nexo-footer#');
});

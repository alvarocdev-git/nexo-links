<?php

namespace App\Http\Controllers;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $page = $request->user()?->page;
        abort_if($page === null, 404);

        $writer = new Writer(new ImageRenderer(
            new RendererStyle(400, 1),
            new SvgImageBackEnd,
        ));

        $svg = $writer->writeString(route('page.show', $page->username));

        $headers = ['Content-Type' => 'image/svg+xml'];

        if ($request->boolean('download')) {
            $headers['Content-Disposition'] = "attachment; filename=\"nexo-{$page->username}-qr.svg\"";
        }

        return response($svg, 200, $headers);
    }
}

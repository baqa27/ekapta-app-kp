<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (PostTooLargeException $e, Request $request) {
            $message = 'Ukuran total file yang diupload terlalu besar. Silakan kompres file lalu coba lagi.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                ], 413);
            }

            return redirect()->back()->withInput($request->except([
                'lampiran_1',
                'lampiran_2',
                'lampiran_3',
                'lampiran_4',
                'lampiran_5',
                'lampiran_6',
                'lampiran_7',
                'dokumen_pendukung',
                'lampiran',
                'ttd',
                'image',
            ]))->with('warning', $message);
        });
    }
}

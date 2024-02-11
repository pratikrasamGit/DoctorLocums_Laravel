<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($request->ajax()) {
			if (method_exists($exception, 'getStatusCode')) {
				switch ($exception->getStatusCode()) {
					// not found
					case '404':
						return response(["Errors" => 'Resource Not Found'], $exception->getStatusCode());
						break;
					case '403':
						return response(["Errors" => 'Forbidden'], $exception->getStatusCode());
						break;
					default:
						return response(["Errors" => $exception->getMessage()], $exception->getStatusCode());
						break;
				}
			} else {
				return response(["Errors" => $exception->getMessage()], 500);
			}
		} else if ($this->isHttpException($exception)) {
			switch ($exception->getStatusCode()) {

				// not authorized
				case '403':
					return \Response::view('pages.404',array(),403);
					break;

				// not found
				case '404':
					return \Response::view('pages.404',array(),404);
					break;

				default:
					return $this->renderHttpException($exception);
					break;
			}
		} else {
			return parent::render($request, $exception);
		}
    }
}

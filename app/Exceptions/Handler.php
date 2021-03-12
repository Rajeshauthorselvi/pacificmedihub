<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Mail;
use Config;
use Auth;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * @throws \Throwable
     */
    public function report(Throwable $e)
    {

     if ($e instanceof \Exception) {

       $debugSetting = Config::get('app.debug');

       Config::set('app.debug', true);
       if (ExceptionHandler::isHttpException($e)) {
           $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::renderHttpException($e), $e);
       } else {
           $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::convertExceptionToResponse($e), $e);
       }

       Config::set('app.debug', $debugSetting);

       $data['content'] = (!isset($content->original)) ? $e->getMessage() : $content->original;

    if (!$e instanceof NotFoundHttpException)
    {
 /*      Mail::send('errors.exception', $data, function ($m) {
         $m->from('dhinesh@authorselvi.com');
         $m->cc('rnaveen@authorselvi.com');
           $m->to('dhinesh@authorselvi.com', 'Error')->subject('PMH- Error'.' -- '.$_SERVER['REMOTE_ADDR'].'--'.date('Y-m-d'));
       });*/
    }
      if (ExceptionHandler::isHttpException($e)) {
          if ($e->getStatusCode() == 404) {
              return response()->view('errors.' . '404', [], 404);
          }
      }


   }

/*         if (ExceptionHandler::isHttpException($e)) {

            return redirect()->route('error.page');
        }*/
        
        return parent::report($e);
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
    public function render($request, Throwable $e)
    {
/*        if (ExceptionHandler::isHttpException($e)) {
            return redirect()->route('error.page');
        }
*/
        return parent::render($request, $e);
    }
}

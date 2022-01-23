<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController
{
    /**
    * @Route("/hello", name="hello")
    */
    public function index(Request $request, LoggerInterface $logger)
    {
			$content = <<< EOM
				<html>
					<head>
						<title>Hello</title>
					</head>
					<body>
						<h1>Hello!</h1>
						<p>this is Symfony sample page.</p>
					</body>
				</html>
EOM;
			$response = new Response(
				$content,
				Response::HTTP_OK,
				array('content-type' => 'text/html')
			);
			$logger->info('aaaaa');
			return $response;
    }

		/**
		 * @Route("/notfound", name="notfound")
		 */
		public function notfound(Request $request){
			$content = <<< EOM
			<html>
				<head>
					<title>ERROR</title>
				</head>
				<body>
					<h1>EROR! 404</h1>
					<p>this is Symfony sample error page.</p>
				</body>
			</html>
EOM;
			$response = new Response(
				$content,
				Response::HTTP_NOT_FOUND,
				array('content-type' => 'text/html')
			);
			return $response;
		}
		/**
		 * @Route("/error", name="error")
		 */
		public function error(Request $request){
			$content = <<< EOM
			<html>
				<head>
					<title>ERROR</title>
				</head>
				<body>
					<h1>EROR! 500</h1>
					<p>this is Symfony sample error page.</p>
				</body>
			</html>
EOM;
			$response = new Response(
				$content,
				Response::HTTP_INTERNAL_SERVER_ERROR,
				array('content-type' => 'text/html')
			);
			return $response;
		}

		/**
		 * @Route("/other/{domain}", name="other")
		 */
		public function other(Request $request, $domain='')
		{
			if ($domain == '') {
				return $this -> redirect('/hello');
			} else {
				return new RedirectResponse("https://{$domain}.com");
			}
		}
}

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
    * @Route("/hello/{msg}", name="hello")
    */
    public function index($msg='Hello')
    {
			return $this->render('hello/index.html.twig',[
				'controller' => 'HelloControleer',
				'action' => 'index',
				'prev_action' => '(none)',
				'message' => $msg
			]);
    }

		/**
		 * @Route("/other/{action}/{msg}", name="other")
		 */
		public function other($action, $msg) {
			return $this ->render('hello/index.html.twig', [
				'controller' => 'HelloControleer',
				'action' => 'other',
				'prev_action' => $action,
				'message' => $msg
			]);
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
}

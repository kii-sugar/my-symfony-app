<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController
{
    /**
    * @Route("/hello", name="hello")
    */
    public function index(Request $req)
    {
			// メソッドチェーン
			$form = $this->createFormBuilder()
			->add('input', TextType::class)
			->add('save', SubmitType::class, [
				'label' => 'Click'
			])
			->getForm();

			if ($req->getMethod() == 'POST') {
				$form->handleRequest($req);
				$msg = 'こんにちは、' . $form->get('input')->getData() . 'さん';
			} else {
				$msg = 'あなたのお名前は？';
			}
			return $this->render('hello/index.html.twig',[
				'title' => 'Hello',
				'message' => $msg,
				'form' => $form->createView() // 作成したフォームのFormViewがテンプレートに渡される
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

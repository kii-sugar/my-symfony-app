<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController {
  /**
  * @Route("/hello", name="hello")
  */
  public function index(Request $req) {

		$data = [
			array(
				'name' => 'Naho',
				'age' => 26,
				'hobby' => 'music, traning',
				'mail' => 'naho@gmail.com'
			),
			array(
				'name' => 'Akihiro',
				'age' => 28,
				'hobby' => 'baseball, game',
				'mail' => 'akihiro@gmail.com'
			),array(
				'name' => 'otochi',
				'age' => 64,
				'hobby' => 'cooking',
				'mail' => 'otochi@gmail.com'
			)
		];
		return $this -> render('hello/index.html.twig', [
			'title' => 'Ultra',
			'message' => 'テンプレートで計算を行います',
			'data' => $data
		]);
  }
}

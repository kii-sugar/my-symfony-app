<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController {
  /**
  * @Route("/hello", name="hello")
  */
  public function index(Request $req, SessionInterface $session) {
		$data = new MyData();

		$form = $this->createFormBuilder($data)
			->add('data', TextType::class)
			->add('save', SubmitType::class,[
				'label' => 'Click!!'
			])
			->getForm();

		if ($req->getMethod() == 'POST') {
			$form->handleRequest($req); // reqをフォームに適合
			$data = $form->getData(); // フォームのPersonインスタンスを取り出す
			if ($data->getData() == '!') {
				$session->remove('data');
			} else {
				$session->set('data', $data->getData());
			}
		} 
		return $this->render('hello/index.html.twig', [
			'title' => 'Hello',
			'data' => $session->get('data'),
			'form' => $form->createview()
		]);
  }
}

// データクラス
class MyData {
	protected $data = '';

	public function getData() {
		return $this->data;
	}

	public function setData($data) {
		$this->data = $data;
	}
}
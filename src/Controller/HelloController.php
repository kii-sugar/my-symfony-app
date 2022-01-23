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
use Symfony\Component\Form\Extension\Core\Type\EmailType;

// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController
{
    /**
    * @Route("/hello", name="hello")
    */
    public function index(Request $req)
    {
			$person = new Person();
			$person->setName('Naho')
				->setAge(26)
				->setMail('naho@gmail.com');

			// Personインスタンスを引数指定し初期値セット。
			// このためにはaddする項目の整合性がとれていないといけない。
			$form = $this->createFormBuilder($person)
				->add('name', TextType::class)
				->add('age', IntegerType::class)
				->add('mail', EmailType::class)
				->add('save', SubmitType::class,[
					'label' => 'Click!!'
				])
				->getForm();

			IF($req->getMethod() == 'POST') {
				$form->handleRequest($req); // reqをフォームに適合
				$obj = $form->getData(); // フォームのPersonインスタンスを取り出す
				$msg = 'Name: ' . $obj->getName() . '<br>'
				. 'Age: ' . $obj->getAge() . '<br>'
				. 'Mail: ' . $obj->getMail();
			} else {
				$msg = 'お名前をどうぞ';
			}
			return $this->render('hello/index.html.twig', [
				'title' => 'Hello',
				'message' => $msg,
				'form' => $form->createview()
			]);
    }
}

// データクラス
class Person
{
	protected $name;
	protected $age;
	protected $mail;

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getAge() {
		return $this->age;
	}

	public function setAge($age) {
		$this->age = $age;
		return $this;
	}

	public function getMail() {
		return $this->mail;
	}

	public function setMail($mail) {
		$this->mail = $mail;
		return $this;
	}
}
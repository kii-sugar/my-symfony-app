<?php

namespace App\Controller;

use App\Entity\Person;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController {
	private $em;
	/**
  * @Route("/hello", name="hello")
  */
	public function index(Request $req, EntityManagerInterface $em) {

		$this->em = $em;

		$repository = $this->em->getRepository(Person::class);
		$data = $repository->findall();
		
		return $this -> render('hello/index.html.twig', [
			'title' => 'Ultra',
			'message' => 'データベースのレコード一覧を表示します',
			'data' => $data
		]);
  }

	/**
  * @Route("/find/{id}", name="find")
  */
	public function find(Request $req, Person $person) {
		
		return $this -> render('hello/find.html.twig', [
			'title' => 'Find Person',
			'data' => $person
		]);
  }
}

class FindForm {
	private $find;

	public function getFind() {
		return $this->find;
	}

	public function setFind($find) {
		$this->find = $find;
	}
}
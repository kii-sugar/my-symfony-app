<?php

namespace App\Controller;

use App\Form\PersonType;
use App\Entity\Person;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController {
	private $em;

	public function __construct(private ManagerRegistry $doctrine) {}
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
  * @Route("/find", name="find")
  */
	public function find(Request $req) {
		
		$formobj = new FindForm();
		$form = $this->createFormBuilder($formobj)
			->add('find', TextType::class)
			->add('save', SubmitType::class, array('label' => 'SEARCH!'))
			->getForm();
		$repository = $this->doctrine->getRepository(Person::class); // Personリポジトリを取得する
		
		if ($req->getMethod() == 'POST') {
			$form->handleRequest($req); // Formにリクエスト情報をハンドリング
			$findstr = $form->getData()->getFind(); // 検索テキストを得る
			$result = $repository->findByName($findstr); // nameの値が等しいレコードだけを取得する（複数行)
		} else {
			$result = $repository->findAllwithSort();
		}

		return $this -> render('hello/find.html.twig', [
			'title' => 'Find Person',
			'form' => $form->createView(),
			'data' => $result
		]);
  }

	/**
	 * @Route("/create", name="create")
	 */
	public function create(Request $req) {
		$person = new Person();
		$form = $this->createForm(PersonType::class, $person);
		$form ->handleRequest($req);
		
		if ($req->getMethod() == 'POST') {
			$person = $form->getData();
			$manager = $this->doctrine->getManager();
			$manager -> persist($person);
			$manager -> flush();
			return $this->redirect('/hello');
		} else {
			return $this->render('hello/create.html.twig', [
				'title' => 'Hello',
				'message' => 'Create Entity',
				'form' => $form->createView(),
			]);
		}
	}

	/**
	 * @Route("/update/{id}", name="update")
	 */
	public function update(Request $req, Person $person) {
		$form = $this->createForm(PersonType::class, $person);
		$form->handleRequest($req);
		
		if ($req->getMethod() =='POST') {
			$person = $form->getData();
			$manager = $this->doctrine->getManager();
			$manager->flush();
			return $this->redirect('/hello');
		} else {
			return $this->render('hello/create.html.twig', [
				'title' => 'Hello',
				'message' => 'Update Entity id = ' . $person->getId(),
				'form' => $form->createView()
			]);
		}
	}

	/**
	 * @Route("/delete/{id}", name="delete")
	 */
	public function delete(Request $req, Person $person) {
		$form = $this->createForm(PersonType::class, $person);

		if ($req->getMethod() == 'POST') {
			$form->handleRequest($req);
			$person = $form->getData();
			$manager = $this->doctrine->getManager();
			$manager->remove($person);
			$manager->flush();
			return $this->redirect('/hello');
		} else {
			return $this->render('hello/create.html.twig', [
				'title' => 'Hello',
				'message' => 'Delete Entity id = ' . $person->getId(),
				'form' => $form->createView()
			]);
		}
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
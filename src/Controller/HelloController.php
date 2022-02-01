<?php

namespace App\Controller;

use App\Form\PersonType;
use App\Form\HelloType;
use App\Entity\Person;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController {

	public function __construct(private ManagerRegistry $doctrine) {}
	/**
  * @Route("/hello", name="hello")
  */
	public function index(Request $req, SessionInterface $session)
	{
		$formobj = new HelloForm();
		// フラッシュメッセージ
		$form = $this->createForm(HelloType::class, $formobj);
		$form->handleRequest($req);

		if( $req->getMethod() == 'POST') {
			$formobj = $form->getData();
			$session->getFlashBag()->add('info.mail', $formobj);
			// info.mailというタイプを指定して好きなオブジェクトを追加する
			// こちらは普通に変数で渡している
			$msg = 'Hello, ' . $formobj->getName() . '!!';
		} else {
			$msg = 'send form';
		}
		
		$repository = $this->doctrine->getRepository(Person::class);
		$data = $repository->findall();

		return $this -> render('hello/index.html.twig', [
			'title' => 'Ultra',
			'data' => $data,
			'message' => $msg,
			'bag' => $session->getFlashBag(),
			'form' => $form->createView()
		]);
  }

	/**
	 * @Route("/clear", name="clear")
	 */
	public function clear(Request $req, SessionInterface $session)
	{
		$session->getFlashBag()->clear();
		return $this->redirect('/hello');
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
		$manager = $this->doctrine->getManager();
		$mapping = new ResultSetMappingBuilder($manager);
		$mapping->addRootEntityFromClassMetadata('App\Entity\Person', 'p');
		if ($req->getMethod() == 'POST') {
			$form->handleRequest($req); // Formにリクエスト情報をハンドリング
			$findstr = $form->getData()->getFind(); // 検索テキストを得る
			$arr = explode(',', $findstr);
			$query = $manager->createNativeQuery(
				'SELECT * FROM person WHERE age between ?1 and ?2', $mapping
			)
				->setParameters(array(1 => $arr[0], 2 => $arr[1]));
			$result = $query->getResult();
			// $result = $repository->findByName($findstr); // nameの値が等しいレコードだけを取得する（複数行)
		} else {
			$query = $manager->createNativeQuery(
				'SELECT * FROM person', $mapping);
			$result = $query->getResult();
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
	public function create(Request $req, ValidatorInterface $validator) {
		$person = new Person();
		$form = $this->createForm(PersonType::class, $person);
		$form ->handleRequest($req);
		
		if ($req->getMethod() == 'POST') {
			$person = $form->getData();
			$errors = $validator->validate($person);

			if (count($errors) == 0) {
				$manager = $this->doctrine->getManager();
				$manager -> persist($person);
				$manager -> flush();
				return $this->redirect('/hello');
			} else {
				return $this->render('hello/create.html.twig', [
					'title' => 'Hello',
					'message' => 'ERROR!',
					'form' => $form->createView(),
				]);
			}
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

class HelloForm
{
	private $name;
	private $mail;

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getMail()
	{
		return $this->mail;
	}

	public function setMail($mail)
	{
		$this->mail = $mail;
	}

	// オーバーライド
	public function __toString()
	{
		return '*** '.$this->name.'[' . $this->mail .'] ***';
	}
}
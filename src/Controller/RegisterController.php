<?php
namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;

class RegisterController extends AbstractController
{
  public function __construct(private ManagerRegistry $doctrine) {}

  /**
   * @Route("/register", name="register")
   */
  public function register(Request $req, UserPasswordHasherInterface $passwordHasher)
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($req);

    if($req->getMethod() == 'POST') {
      if ($form->isValid()) {
        // パスワードを暗号化
        $hashedPassword = $passwordHasher->hashPassword(
          $user,
          $user->getPassword()
        );
        $user->setPassword($hashedPassword);
        $manager = $this->doctrine->getManager();
        $manager->persist($user); // 登録
        $manager->flush(); // 反映
        return $this->redirectToRoute('login'); // @Routeのnameで指定している名前
      }
    }

    return $this->render(
      'registration/register.html.twig',
      array(
        'form' => $form->createView()
      )
    );
  }
}
?>


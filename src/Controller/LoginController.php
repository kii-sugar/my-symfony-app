<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // 最後に実行された認証処理のエラーを返す。エラーが起きてなければnull
        // AuthenticationExceptionの例外クラスインスタンスが渡されることになる、エラーの場合は
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($this->getUser() == null) {
            $user = '未ログインです';
        } else {
            $user = 'logined: ' . $this->getUser()->getUserIdentifier();
        }

        return $this->render('login/index.html.twig', [
            'error' => $error,
            'user' => $user
        ]);
    }
}

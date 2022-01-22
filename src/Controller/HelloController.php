<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController
{
		// @Roteアノテーションで{値}を指定して、アクションメソッドの引数$変数に渡される
		// 注意：パスパラメータが無いとエラーになる->デフォルト値を設定しておくとよい
    /**
    * @Route("/hello/{name}/{pass}", name="hello")
    */
    public function index($name=('(noname)'), $pass='(no password)')
    {
      $result = '<html><body>';
			$result .= '<h1>Subscribed Services</h1>';
			$result .= '<ol>';
			$result .= '<li>name: ' . $name . '</li>';
			$result .= '<li>pwd : ' . $pass . '</li>';
			$result .= '</ol>';
			$result .= '</body></html>';
      return new Response($result);
    }
}

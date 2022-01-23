<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Serializer; // オブジェクトのシリアライズ(テキストデータに変換する処理)
use Symfony\Component\Serializer\Encoder\XmlEncoder; // XMLデータの変換
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer; // オブジェクトへの変換

// AbstractController
// Twigというテンプレートエンジンを利用して画面をレンダリング表示するためにも必要
class HelloController extends AbstractController
{
    /**
    * @Route("/hello", name="hello")
    */
    public function index(Request $request)
    {
			$encoders = array(new XmlEncoder());
			$normalizers = array(new ObjectNormalizer());
			$serializer = new Serializer($normalizers, $encoders);

			$data = array(
				'name' => array(
					'first' => 'Hanako',
					'second' => 'Tanaka'
				),
				'age' => 29
			);

			$response = new Response();
			$response->headers->set('Content-Type', 'xml');
			$result = $serializer->serialize($data, 'xml');
			$response->setContent($result);
			return $response;
    }

		/**
		 * @Route("/other/{domain}", name="other")
		 */
		public function other(Request $request, $domain='')
		{
			if ($domain == '') {
				return $this -> redirect('/hello');
			} else {
				return new RedirectResponse("https://{$domain}.com");
			}
		}
}

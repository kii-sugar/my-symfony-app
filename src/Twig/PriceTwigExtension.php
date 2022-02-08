<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Twigfilter;
use App\Twig\HeloTokenParser;

class PriceTwigExtension extends AbstractExtension
{
  public function getFilters()
  {
    return [
      // priceという名前のフィルター。
      // priceFilterはメソッド名
      new TwigFilter('price', [$this, 'priceFilter'])
    ];
  }

  //引数 必ず一つは必要になる(一つ目の引数がフィルターにかけられる元)
  public function priceFilter($number, $header='￥', $decimals=0)
  {
    $price = number_format($number, $decimals, '-', ',');
    return $header . $price;
  }
}
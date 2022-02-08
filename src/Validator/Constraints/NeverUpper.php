<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

# messageフィールドが宣言されているだけ
# value変数を埋め込んである、Constraintクラスの$messageだからできる
/**
 * @Annotation
 */
class NeverUpper extends Constraint
{
  public $message = '* "{{value}}"には大文字が含まれています。';
  public $mode = 'strict';
}
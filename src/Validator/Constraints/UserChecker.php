<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserChecker extends Constraint
{
  public $message = '* "{{val_A}}" は "{{val_B}} {{do}}". *';

  // これを記述することで、$valueはチェックするエンティティのインスタンスになる。getUsername()ができる
  public function getTargets()
  {
    return self::CLASS_CONSTRAINT;
  }
}
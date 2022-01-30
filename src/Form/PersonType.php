<?php
namespace App\Form;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersonType extends AbstractType {
  public function buildForm(
      FormBuilderInterface $builder, /* FormBuilderインスタンス */ 
      array $options /* 設定などをまとめた配列 */) {
    $builder
    ->add('name', TextType::class)
		->add('mail', TextType::class)
		->add('age', IntegerType::class)
		->add('save', SubmitType::class, array('label' => 'CLICK'));
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => Person::class,
    ));
  }
}
?>
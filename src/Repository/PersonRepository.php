<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    // nameフィールドで検索するメソッドを追加
    public function findByName($value) {
        $arr = explode (',', $value); //第1引数の文字で第２引数の文字列を配列に分割する関数
        return $this->createQueryBuilder('p') // Personテーブルをpと指定
            ->where("p.name in (?1, ?2)")
            ->setParameters(array(1 => $arr[0], 2 => $arr[1]))
            ->getQuery() // Queryクラスのインスタンスを取得
            ->getResult(); // 実行(エンティティのリストを返す)
    }

    // where文を直接書くことを避けるため、Exprクラスのexprメソッドを使用する
    public function findByName2($value) {
        $arr = explode(',', $value);
        $builder = $this->createQueryBuilder('p'); // buidlerを変数に代入
        return $builder // builderのwhereメソッドを呼び出す形にする
            ->where($builder->expr()->in('p.name', $arr))
            ->getQuery()->getResult();
    }

    // 年齢条件文で〇歳以上●歳以下
    public function findByAge($value) {
        $arr = explode(',', $value);
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->gte('p.age', '?1'))
            ->andWhere($builder->expr()->lte('p.age', '?2'))
            ->setParameters(array(1 => $arr[0], 2 => $arr[1]))
            ->getQuery()->getResult();
    }

    // 名前とメールアドレスで検索する
    public function findByNameOrMail($value) {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->like('p.name', '?1'))
            ->orWhere($builder->expr()->like('p.mail', '?2'))
            ->setParameters(array(
                1 => '%'.$value.'%',
                2 => '%'.$value.'%'
                )) // 同じ値を設定する場合でも別々のプレースホルダを用意する
            ->getQuery()->getResult();
    }

    //年齢の降順に並べる
    public function findAllwithSort() {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->orderBy('p.age', 'DEsC')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

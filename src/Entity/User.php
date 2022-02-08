<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as MyAssert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('username')] // エンティティがユニーク（重複することが無い）ことを表すアノテーション
/**
 * @MyAssert\UserChecker
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface // ユーザー認証のために必要
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'boolean')]
    private $isActivated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    // デフォルトでisActiveをtrueにしておく
    public function __construct()
    {
        $this->isActivated = true;
    }


    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }
    
    // パスワードのエンコードを行う際に使われるソルトと呼ばれる値を取得する.
    // 今回は使っていないので単にnull返却
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getRoles():array
    {
        $roles = [];
        // guarantee every user at least has ROLE_USER
        if($this->username == 'admin') {
            $roles[] = 'ROLE_ADMIN';
        } else {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    // 外部に漏れては困る重要な情報を消去するための処理を用意するためのメソッド
    public function eraseCredentials()
    {
    }

    // シリアライズ(直列化、オブジェクトをテキストの値に変換すること)のメソッド
    public function serialie()
    {
        // serialize関数でシリアライズ
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ));
    }

    // アンシリアイライズ(シリアライズしたデータを復元すること)のメソッド
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ) = unserialize($serialied, array('allowed_classes' => false));
        // unserializeメソッドを使ってアンシリアライズ
    }

    public function __toString()
    {
        return '[' . $this->getUserName() . ', email: ' . $this->getEmail() . ']';
    }
}

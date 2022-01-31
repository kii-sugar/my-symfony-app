<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"入力してください。")]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Email(message:"メールアドレスを入力してください。")]
    #[Assert\NotBlank(message:"入力してください。")]
    private $mail;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Type(type: "integer", message:"整数を入力してください。")]
    #[Assert\NotBlank(message:"入力してください。")]
    private $age;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }
}

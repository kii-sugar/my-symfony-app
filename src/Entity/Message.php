<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as MyAssert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // rargetEntity 関連付けられるエンティティ
    // inversedBy 関連付けられた先からこのエンティティに関連付けて用意されているプロパティ
    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'messages')]
    private $person;

    /**
     * @ORM\Column(type="string", length=255)
     * @MyAssert\NeverUpper(mode="strict")
     */
    private $content;

    #[ORM\Column(type: 'datetime')]
    private $posted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPosted(): ?\DateTimeInterface
    {
        return $this->posted;
    }

    public function setPosted(\DateTimeInterface $posted): self
    {
        $this->posted = $posted;

        return $this;
    }
}

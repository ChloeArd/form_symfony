<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide !")]
    private $firstname;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank(message: "Le nom de famille ne peut pas être vide !")]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(binaryFormat: true, maxSize: "2M",
        mimeTypes: [".pdf", ".doc", ".docx", ".txt"],
        mimeTypesMessage: "Le type mime du fichier n'est pas valide ({{ type }}). Les types mime autorisés sont {{ types }}.",
        maxSizeMessage: "Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximale autorisée est {{ limit }} {{ suffix }}")]
    private $bioFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBioFile(): ?File
    {
        return $this->bioFile;
    }

    public function setBioFile(?File $bioFile): self
    {
        $this->bioFile = $bioFile;

        return $this;
    }

    public function getImage(): ?File
    {
        return $this->image;
    }

    public function setImage(?File$image): self
    {
        $this->image = $image;

        return $this;
    }

    //Pour garantir que l' bioFile Fileobjet est valide et qu'il est inférieur à une certaine taille
    // de fichier et à un PDF valide
    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraint('bioFile', new Assert\File([
            'maxSize' => '1024k',
            'mimeTypes' => [
                'application/pdf',
                'application/x-pdf',
            ],
            'mimeTypesMessage' => "Merci d'envoyer un PDF valide !",
        ]));
    }
}

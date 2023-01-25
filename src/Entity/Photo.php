<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[Vich\Uploadable]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Vich\UploadableField(
        mapping: 'uploaded_photos',
        fileNameProperty: 'imageName',
        size: 'imageSize'
    )]
    private ?File $imageFile = null;


    #[ORM\Column(length: 255)]
    private ?string $imageName = null;

    
    #[ORM\Column(type: 'integer')]
    private ?int $imageSize = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $post_at = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPostAt(): ?string
    {
        return date_format($this->post_at, 'Y-m-d H:i:s');
    }

    public function setPostAt(\DateTimeImmutable $post_at): self
    {
        $this->post_at = $post_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Get the value of imageFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }



    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->post_at = new \DateTimeImmutable();
        }
    }

    /**
     * Get the value of imageName
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set the value of imageName
     *
     * @return  self
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get the value of imageSize
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * Set the value of imageSize
     *
     * @return  self
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }
}

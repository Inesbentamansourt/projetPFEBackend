<?php

namespace App\Entity;

use App\Repository\UploadFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=UploadFileRepository::class)
 */
class UploadFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created" , type="datetime")
     */
    private $created_at;

    /**
    * @Gedmo\Timestampable(on="update")
    * @ORM\Column(name="updated" , type="datetime")
    */
    private $updated_at;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brochurefilename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrochurefilename(): ?string
    {
        return $this->brochurefilename;
    }

    public function setBrochurefilename(string $brochurefilename): self
    {
        $this->brochurefilename = $brochurefilename;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}

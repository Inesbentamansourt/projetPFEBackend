<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;



    /**
     * @ORM\Column(type="string")
     */
    private $start_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $end_date;

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
     * @ORM\OneToMany(targetEntity=Reunion::class, mappedBy="evenement")
     */
    private $reunion;

    /**
     * @ORM\OneToMany(targetEntity=Tache::class, mappedBy="evenement", orphanRemoval=true)
     */
    private $tache;

    /**
     * @ORM\OneToMany(targetEntity=Responsable::class, mappedBy="evenement")
     */
    private $responsable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="evenement", orphanRemoval=true)
     */
    private $categories;



    public function __construct()
    {
       
        $this->reunion = new ArrayCollection();
        $this->tache = new ArrayCollection();
        $this->responsable = new ArrayCollection();
        $this->categories = new ArrayCollection();
      
 
    }


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    public function setStartDate(string $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    public function setEndDate(string $end_date): self
    {
        $this->end_date = $end_date;

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

    /**
     * @return Collection<int, Reunion>
     */
    public function getReunion(): Collection
    {
        return $this->reunion;
    }

    public function addReunion(Reunion $reunion): self
    {
        if (!$this->reunion->contains($reunion)) {
            $this->reunion[] = $reunion;
            $reunion->setEvenement($this);
        }

        return $this;
    }

    public function removeReunion(Reunion $reunion): self
    {
        if ($this->reunion->removeElement($reunion)) {
            // set the owning side to null (unless already changed)
            if ($reunion->getEvenement() === $this) {
                $reunion->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTache(): Collection
    {
        return $this->tache;
    }

    public function addTache(Tache $tache): self
    {
        if (!$this->tache->contains($tache)) {
            $this->tache[] = $tache;
            $tache->setEvenement($this);
        }

        return $this;
    }

    public function removeTache(Tache $tache): self
    {
        if ($this->tache->removeElement($tache)) {
            // set the owning side to null (unless already changed)
            if ($tache->getEvenement() === $this) {
                $tache->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Responsable>
     */
    public function getResponsable(): Collection
    {
        return $this->responsable;
    }

    public function addResponsable(Responsable $responsable): self
    {
        if (!$this->responsable->contains($responsable)) {
            $this->responsable[] = $responsable;
            $responsable->setEvenement($this);
        }

        return $this;
    }

    public function removeResponsable(Responsable $responsable): self
    {
        if ($this->responsable->removeElement($responsable)) {
            // set the owning side to null (unless already changed)
            if ($responsable->getEvenement() === $this) {
                $responsable->setEvenement(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setEvenement($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getEvenement() === $this) {
                $category->setEvenement(null);
            }
        }

        return $this;
    }

   
    
}

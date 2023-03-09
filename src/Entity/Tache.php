<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=TacheRepository::class)
 */
class Tache
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
     * @ORM\Column(type="string", length=255)
     */
    private $estimate_start_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estimate_end_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $achivement_start_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $achivement_end_date;

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
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="tache")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evenement;

    /**
     * @ORM\Column(type="json" , nullable=true)
     */
    private $piecesJointes = [];





    /**
     * @ORM\OneToMany(targetEntity=Historique::class, mappedBy="tache")
     */
    private $historiques;

    /**
     * @ORM\ManyToOne(targetEntity=Tache::class)
     */
    private $tache;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="taches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    public function __construct()
    {
        $this->tache = new ArrayCollection();
        $this->historiques = new ArrayCollection();
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

    public function getEstimateStartDate(): ?string
    {
        return $this->estimate_start_date;
    }

    public function setEstimateStartDate(string $estimate_start_date): self
    {
        $this->estimate_start_date = $estimate_start_date;

        return $this;
    }

    public function getEstimateEndDate(): ?string
    {
        return $this->estimate_end_date;
    }

    public function setEstimateEndDate(string $estimate_end_date): self
    {
        $this->estimate_end_date = $estimate_end_date;

        return $this;
    }

    public function getAchivementStartDate(): ?string
    {
        return $this->achivement_start_date;
    }

    public function setAchivementStartDate(string $achivement_start_date): self
    {
        $this->achivement_start_date = $achivement_start_date;

        return $this;
    }

    public function getAchivementEndDate(): ?string
    {
        return $this->achivement_end_date;
    }

    public function setAchivementEndDate(string $achivement_end_date): self
    {
        $this->achivement_end_date = $achivement_end_date;

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

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }


    public function getpiecesJointes(): array
    {
        $piecesJointes = $this->piecesJointes;
        // guarantee every user at least has ROLE_USER
        $piecesJointes[] = 'ROLE_USER';

        return array_unique($piecesJointes);
    }

    public function setpiecesJointes(array $piecesJointes): self
    {
        $this->piecesJointes = $piecesJointes;

        return $this;
    }
  


    /**
     * @return Collection<int, Historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): self
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques[] = $historique;
            $historique->setTache($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): self
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getTache() === $this) {
                $historique->setTache(null);
            }
        }

        return $this;
    }

    public function getTache(): ?self
    {
        return $this->tache;
    }

    public function setTache(?self $tache): self
    {
        $this->tache = $tache;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}

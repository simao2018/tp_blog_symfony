<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articlesWritten")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    // /**
    //  * @ORM\ManyToMany(targetEntity=User::class, mappedBy="liked")
    //  */
    // private $usersLike;

    // /**
    //  * @ORM\ManyToMany(targetEntity=User::class, mappedBy="shared")
    //  */
    // private $usersShare;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->usersLike = new ArrayCollection();
        $this->usersShare = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

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

    // /**
    //  * @return Collection|User[]
    //  */
    // public function getUsersLike(): Collection
    // {
    //     return $this->usersLike;
    // }

    // public function addUsersLike(User $usersLike): self
    // {
    //     if (!$this->usersLike->contains($usersLike)) {
    //         $this->usersLike[] = $usersLike;
    //         $usersLike->addLiked($this);
    //     }

    //     return $this;
    // }

    // public function removeUsersLike(User $usersLike): self
    // {
    //     if ($this->usersLike->removeElement($usersLike)) {
    //         $usersLike->removeLiked($this);
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection|User[]
    //  */
    // public function getUsersShare(): Collection
    // {
    //     return $this->usersShare;
    // }

    // public function addUsersShare(User $usersShare): self
    // {
    //     if (!$this->usersShare->contains($usersShare)) {
    //         $this->usersShare[] = $usersShare;
    //         $usersShare->addShared($this);
    //     }

    //     return $this;
    // }

    // public function removeUsersShare(User $usersShare): self
    // {
    //     if ($this->usersShare->removeElement($usersShare)) {
    //         $usersShare->removeShared($this);
    //     }

    //     return $this;
    // }
}

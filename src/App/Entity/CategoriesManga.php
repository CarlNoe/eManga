<?php

namespace App\Entity;

use App\Repository\CategoriesMangaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: CategoriesMangaRepository::class)]
#[ORM\Table(name: 'categories_manga')]
class CategoriesManga
{
    #[Id, ManyToOne(targetEntity: Categories::class)]
    private Categories|null $categories = null;

    #[Id, ManyToOne(targetEntity: manga::class)]
    private manga|null $manga = null;

    public function __construct(Categories $categories, manga $manga)
    {
        $this->categories = $categories;
        $this->manga = $manga;
    }
}

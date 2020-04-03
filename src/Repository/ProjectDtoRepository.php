<?php


namespace App\Repository;


use App\Dto\DtoInterface;
use App\Dto\ProjectDto;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProjectDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    const FILTRE_DTO_INIT_HOME = 'home';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    const ALIAS = 'p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function countForDto(DtoInterface $dto)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_selectCount();

        $this->initialise_where();

        $this->initialise_orderBy();

        try {
            return $this->builder
                ->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    public function findForCombobox(DtoInterface $dto)
    {
        $this->dto = $dto;

        $this->initialise_selectCombobox();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null)
    {
        $this->dto = $dto;

        $this->initialise_selectAll();

        $this->initialise_where();

        $this->initialise_orderBy();

        if (empty($page)) {
            $this->builder
                ->getQuery()
                ->getResult();
        } else {
            $this->builder
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit);
        }

        return new Paginator($this->builder);
    }

    public function findAllForDto(DtoInterface $dto, string $filtre = self::FILTRE_DTO_INIT_HOME)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        switch ($filtre) {
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $this->initialise_selectAll();
                break;
            case self::FILTRE_DTO_INIT_HOME:
                $this->initialise_select_home();
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $this->initialise_selectAll();
                break;
        }

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();

    }

    private function initialise_select_home()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                UserRepository::ALIAS
            )
            ->Join(self::ALIAS . '.users', UserRepository::ALIAS);
    }

    private function initialise_selectAll()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                'distinct ' . self::ALIAS,
                UserRepository::ALIAS
            )
            ->Join(self::ALIAS . '.users', UserRepository::ALIAS);


    }

    private function initialise_selectCombobox()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct '. self::ALIAS.'.id, '.self::ALIAS.'.name')
            ->Join(self::ALIAS . '.users', UserRepository::ALIAS);;
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->Join(self::ALIAS . '.users', UserRepository::ALIAS);;
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;
        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_user();


        $this->initialise_where_enable();


        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }

    }


    private function initialise_where_user()
    {
        if (!empty($this->dto->getUser())) {
            $this->builder->andWhere(UserRepository::ALIAS . '.id = :userid');
            $this->addParams('userid', $this->dto->getUser()->getId());
        }
    }


    private function initialise_where_enable()
    {
        if (!empty($this->dto->getEnable())) {
            if ($this->dto->getEnable() == ProjectDto::TRUE) {

                $this->builder->andWhere(self::ALIAS . '.enable= true');
            } elseif ($this->dto->getEnable() == ProjectDto::FALSE) {
                $this->builder->andWhere(self::ALIAS . '.enable= false');
            }
        }

    }


    private function initialise_where_search()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (!empty($dto->getWordSearch())) {
            $builder
                ->andWhere(
                    self::ALIAS . '.content like :search' .
                    ' OR ' . self::ALIAS . '.name like :search'
                   );

            $this->addParams('search', '%' . $dto->getWordSearch() . '%');
        }

    }



    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(self::ALIAS . '.name', 'ASC');
    }


}
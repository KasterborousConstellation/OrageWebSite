<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use \Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function translateRoles(array $roles) : array
    {
        $roleMap = [
            'ROLE_USER' => 'User',
            'ROLE_ADMIN' => 'Admin'
        ];



        $translatedRoles = array_map(function($role) use ($roleMap) {
            return $roleMap[$role] ?? "";},
            $roles);
        return $translatedRoles;
    }
    public function paginateUserData($request,$param) : PaginationInterface
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        return $this->paginator->paginate(
            $this->createQueryBuilder('u')
            ->select('u.id','u.username','u.email','u.roles')
            ->where('u.roles LIKE :param or u.username LIKE :param or u.email LIKE :param')
            ->setParameter('param', '%'.$param.'%')
            ,
            $page,
            $limit,
            [
                'distinc' => true,
                'sortFieldAllowList' => ['u.username'],


            ]
        );
    }
    public function getUserByEmailorUsername(string $emailOrUsername) : ?User{
        return $this->createQueryBuilder('u')
            ->where('u.email = :param or u.username = :param')
            ->setParameter('param', $emailOrUsername)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}

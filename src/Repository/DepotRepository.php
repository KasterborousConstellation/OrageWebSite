<?php
namespace App\Repository;
use App\Entity\Chapitre;
use App\Entity\Depot;
use App\Entity\SupportedFileType;
use App\Form\FileInputType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
/**
 * @extends ServiceEntityRepository<Depot>
 */
class DepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly EntityManagerInterface $em)
    {
        parent::__construct($registry, Depot::class);
    }

    public function getLatestByIdentifier(string $identifier): ?Depot
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.identifier = :identifier')
            ->setParameter('identifier', $identifier)
            ->orderBy('d.version', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * Return all versions of a Depot by its identifier, ordered by version descending.
     * First is lastest version, last is the oldest version.
     * @return Depot[]
     */
    public function getAllByIdentifier(string $identifier): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.identifier = :identifier')
            ->setParameter('identifier', $identifier)
            ->orderBy('d.version', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function getLastestByChapter(Chapitre $chapter): array{
        return $this->createQueryBuilder('u')
            ->leftJoin('App\Entity\Chapitre','c')
            ->where('c.id = :id ')
            ->setParameter('id',$chapter->getId())
            ->orderBy('u.version', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function createDepotFromForm(Form $form){
        $file = $form->get('file')->getData();

        $identifier = $form->get('identifier')->getData();
        $version = $form->get('version')->getData();
        return $this->createDepot($identifier, $version, $file);
    }
    /**
     * Create a new Depot for a given Chapitre.
     * The identifier is taken from the Chapitre's Cours code.
     * The version is incremented from the latest version of the same identifier.
     * The depot is linked to chapter as latest depot and persisted.
     * @param Chapitre $chapitre
     * @param File $file
     * @return Depot
     *
     */
    public function createDepotFromChapter(Chapitre $chapitre, File $file): Depot
    {
        $obj = $this->getLastestByChapter($chapitre);
        $version = $obj ? $obj->getVersion() + 1 : 1;
        $identifier = $chapitre->getNomChapitre();
        $depot =  $this->createDepot($identifier, $version, $file);
        $chapitre->addDepot($depot);
        $this->em->persist($chapitre);
        $this->em->flush();
        return $depot;
    }
    /*
     * @param
     */
    public function createDepotFromIdentifier(string $identifier,File $file,SupportedFileType $fileType): Depot
    {
        $obj = $this->getLatestByIdentifier($identifier);
        $version = $obj ? $obj->getVersion() + 1 : 1;
        return $this->createDepot($identifier, $version, $file,$fileType);
    }
    public static function generateRandomString($length = 10) :string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * @throws UniqueConstraintViolationException
     */
    private function createDepot(string $identifier, int $version, File $file,SupportedFileType $fileType): Depot
    {
        $intersection = $this->createQueryBuilder('u')->where('u.identifier = :identifier and u.version = :version')
            ->setParameter('version',$version)
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getResult();
        if($intersection != null){
            throw new UniqueConstraintViolationException();
        }
        $depot = new Depot();
        $dir = "files/".$this->generateRandomString(20);
        $name = str_replace(' ','_',$file->getClientOriginalName());
        $file->move($dir, $name);
        $depot->setPathPDF($dir .'/'. $name);
        $depot->setIdentifier($identifier);
        $depot->setVersion($version);
        $depot->setFileType($fileType);
        $depot->setDisplayName('FileNotNamed');
        $depot->setHeureDepot(new \DateTimeImmutable());
        $this->em->persist($depot);
        $this->em->flush();
        return $depot;
    }
    public function getFileFromDepot(Depot $depot) : File{
        $path = $depot->getPathPDF();
        return new File($path);
    }
}

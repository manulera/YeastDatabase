<?php

namespace App\Command;

use App\Entity\Locus;
use App\Entity\LocusName;
use App\Entity\Marker;
use App\Entity\Oligo;
use App\Entity\Plasmid;
use App\Entity\PombaseId;
use App\Entity\Promoter;
use App\Entity\Strain;
use App\Entity\StrainSource;
use App\Entity\StrainSourceTag;
use App\Entity\Tag;
use App\Entity\User;
use App\Service\Genotyper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class PopulateDatabaseCommand extends Command
{
    // The entity manager
    private $em;

    // The genotype generator
    private $genotyper;

    // The app parameters
    private $params;

    public function __construct(EntityManagerInterface $em, Genotyper $genotyper, ParameterBagInterface $params)
    {
        parent::__construct();
        $this->em = $em;
        $this->genotyper = $genotyper;
        $this->params = $params;
    }

    protected function configure(): void
    {
        $this->setName('app:populate-database')
        ->setDescription('Creates a series of entities in the database');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Add the main antibiotic resistance markers of Pombe
        foreach (['KanMX','HphMX','NatMX'] as $marker_name) {
            $marker = new Marker;
            $marker->setName($marker_name);
            $this->em->persist($marker);
        }

        // Add popular promoters
        foreach (['Pnmt1','Pnmt41','Pnmt81'] as $promoter_name) {
            $promoter = new Promoter;
            $promoter->setName($promoter_name);
            $this->em->persist($promoter);
        }

        // Add popular tags
        foreach ([['RFP','red'],['mCherry','red'],['GFP','green'],['eGFP','green'],['GST','']] as $tag_info) {
            $tag = new Tag;
            $tag->setName($tag_info[0]);
            $tag->setColor($tag_info[1]);
            $this->em->persist($tag);
        }

        // Types of StrainSources
        foreach (['Import','Mating','MolBiol','Plasmid','MarkerSwitch'] as $strain_source_tag_name) {
            $strain_source_tag = new StrainSourceTag;
            $strain_source_tag->setName($strain_source_tag_name);
            $this->em->persist($strain_source_tag);
        }

        // Add dummy oligonucleotides
        $oligo1 = new Oligo();
        $oligo1->setSequence('ACGTACGT');
        $oligo1->setName('O-1');
        $oligo1->setDetails('An example oligo');
        $this->em->persist($oligo1);

        $oligo2 = new Oligo();
        $oligo2->setSequence('AATTATATATATTA');
        $oligo2->setName('O-2');
        $oligo2->setDetails('An example oligo');
        $this->em->persist($oligo2);

        // Add dummy plasmids
        $plasmid = new Plasmid();
        $plasmid->setCode('P-1');
        $plasmid->setName('pFA6a-NatMX6');
        $plasmid->setDetails('A plasmid with for PCR-based gene targeting');
        $this->em->persist($plasmid);

        $plasmid = new Plasmid();
        $plasmid->setCode('P-2');
        $plasmid->setName('pFA6a-KanMX6');
        $plasmid->setDetails('A plasmid with for PCR-based gene targeting');
        $this->em->persist($plasmid);

        $plasmid = new Plasmid();
        $plasmid->setCode('P-3');
        $plasmid->setName('pFA6a-HphMX6');
        $plasmid->setDetails('A plasmid with for PCR-based gene targeting');
        $this->em->persist($plasmid);

        $plasmid = new Plasmid();
        $plasmid->setCode('P-4');
        $plasmid->setName('pFA6a-KanMX6-Pnmt1');
        $plasmid->setDetails('A plasmid with for PCR-based gene targeting to change a gene promoter');
        $this->em->persist($plasmid);

        $plasmid = new Plasmid();
        $plasmid->setCode('P-5');
        $plasmid->setName('pFA6a-KanMX6-Pnmt41');
        $plasmid->setDetails('A plasmid with for PCR-based gene targeting to change a gene promoter');
        $this->em->persist($plasmid);

        $plasmid = new Plasmid();
        $plasmid->setCode('P-6');
        $plasmid->setName('pFA6a-KanMX6-Pnmt81');
        $plasmid->setDetails('A plasmid with for PCR-based gene targeting to change a gene promoter');
        $this->em->persist($plasmid);

        // Add dummy user
        $user = new User();
        $user->setUsername('dummy');
        $user->setEmail('dummy@dummy.com');
        $user->setPassword('dummy@dummy.com');
        $this->em->persist($user);

        // We flush here to get the import StrainSourceTag object below
        $this->em->flush();

        // Add two wt strains h- and h+
        foreach (['h-','h+'] as $mating_type)
        {
            $strain = new Strain();
            $strain->setMType($mating_type);
            $strain->updateGenotype($this->genotyper);

            $strain_source = new StrainSource();
            $tag = $this->em->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'Import']);
            $strain_source->addStrainSourceTag($tag);
            $strain_source->setCreator($user);
            $strain_source->setDate(new \DateTime(date('Y-m-d H:i:s')));
            $strain_source->addStrainsOut($strain);

            $this->em->persist($strain_source);
            $this->em->persist($strain);
        }

        // Add the Pombe loci
        $finder = new Finder();
        $finder->files()->in($this->params->get('data_dir') . '/genes_json');
        foreach ($finder as $file) {

            $json = json_decode(file_get_contents($file->getPathname()), true);
            $locus = new Locus();

            // Not all loci have a name
            if ($json['name']) {
                $name = new LocusName();
                $name->setName($json['name']);
                $locus->setName($name);
            }

            $pombase_id = new PombaseId();
            $pombase_id->setPombaseId($json['id']);
            $locus->setPombaseId($pombase_id);

            $this->em->persist($locus);
        }

        $this->em->flush();
        // this method must return an integer number with the "exit status code"
        // of the command.

        // return this if there was no problem running the command
        return 0;

        // or return this if some error happened during the execution
        // return 1;
    }
}
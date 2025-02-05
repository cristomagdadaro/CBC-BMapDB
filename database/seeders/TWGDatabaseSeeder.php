<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\TWGExpert;
use App\Models\TWGProduct;
use App\Models\TWGProject;
use App\Models\TWGService;
use App\Models\User;
use Illuminate\Database\Seeder;

enum InstituteList: string
{
    case BPI = 'Bureau of Plant Industry';
    case PCA = 'Philippine Coconut Authority';
    case PRRI = 'Philippine Rubber Research Institute';
    case RFOII = 'RFO Region II';
    case RFOIII = 'RFO Region III';
    case RFOIVA = 'RFO Region IV-A';
    case RFOIVB = 'RFO Region IV-B';
    case RFOVI = 'RFO Region VI';
    case RFOIX = 'RFO Region IX';
    case RFOX =  'RFO Region X';

}

class TWGDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*$this->call([
            TWGExpertSeeder::class,
            TWGProjectSeeder::class,
            TWGProductSeeder::class,
            TWGServiceSeeder::class,
        ]);*/

        $this->insertExperts();
        $this->insertProject();
        $this->insertProducts();
        $this->insertServices();
    }

    private function insertExperts()
    {
        $experts = [
            [
                'user_id' => 14,
                'institution' => $this->getInstituteId(InstituteList::BPI->value),
                'name' => 'Geronima Eusebio',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09123456789",
                'email' => "bpi.do@buplant.da.gov.ph",
            ],
            [
                'user_id' => 17,
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'name' => 'Maria Czet Fulleros',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09223456789",
                'email' => "pca1.ofad@gmail.com",
            ],
            [
                'user_id' => 17,
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'name' => 'Maria Leonila Imperial',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09323456789",
                'email' => "pca2.ofad@gmail.com"
            ],
            [
                'user_id' => 17,
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'name' => 'Cristeta A. Cueto',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09423456789",
                'email' => "pca3.ofad@gmail.com"
            ],
            [
                'user_id' => 17,
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'name' => 'Maria Buena Areza-Ubaldo',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09523456789",
                'email' => "pca4.ofad@gmail.com"
            ],
            [
                'user_id' => 17,
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'name' => 'Ma. Leonila Imperial',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09623456789",
                'email' => "pca5.ofad@gmail.com"
            ],
            [
                'user_id' => 30,
                'institution' => $this->getInstituteId(InstituteList::PRRI->value),
                'name' => 'Jess Bryan M. Alvarino',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09723456789",
                'email' => "cheryleusala@gmail.com"
            ],
            [
                'user_id' => 30,
                'institution' => $this->getInstituteId(InstituteList::PRRI->value),
                'name' => 'Vivian M. Calambro',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09823456789",
                'email' => null
            ],
            [
                'user_id' => 7,
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'name' => 'Roynic Y. Aquino',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09923456789",
                'email' => null
            ],[
                'user_id' => 7,
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'name' => 'Reymar Gulatera',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09103456789",
                'email' => null
            ],
            [
                'user_id' => 8,
                'institution' => $this->getInstituteId(InstituteList::RFOIII->value),
                'name' => 'Emily A. Soriano',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09113456789",
                'email' => null
            ],[
                'user_id' => 8,
                'institution' => $this->getInstituteId(InstituteList::RFOIII->value),
                'name' => 'Veronica Mangune',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09133456789",
                'email' => null
            ],[
                'user_id' => 19,
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'name' => 'Gina R. Rosarda',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09143456789",
                'email' => null
            ],[
                'user_id' => 20,
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'name' => 'Ryan V. Rasgo',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09153456789",
                'email' => null
            ],[
                'user_id' => 24,
                'institution' => $this->getInstituteId(InstituteList::RFOIX->value),
                'name' => 'Ernie C. Camacho',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09163456789",
                'email' => null
            ],[
                'user_id' => 24,
                'institution' => $this->getInstituteId(InstituteList::RFOIX->value),
                'name' => 'Irene M. Dinoy',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09173456789",
                'email' => null
            ],[
                'user_id' => 25,
                'institution' => $this->getInstituteId(InstituteList::RFOX->value),
                'name' => 'Eleazer A. Jumalon',
                'position' =>  'NA',
                'educ_level' => "Doctoral",
                'expertise' => "Plant Breeding",
                'research_interest' => "Plant Breeding",
                'mobile' => "09183456789",
                'email' => null
            ]
        ];

        foreach ($experts as $expert) {
            TWGExpert::create([
                'user_id' => $expert['user_id'],
                'institution' => $expert['institution'],
                'name' => $expert['name'],
                'position' => $expert['position'],
                'educ_level' => $expert['educ_level'],
                'expertise' => $expert['expertise'],
                'research_interest' => $expert['research_interest'],
                'mobile' => $expert['mobile'],
                'email' => $expert['email'],
            ]);
        }
    }

    private function insertProject()
    {
        $projects = [
            [
                'user_id' => $this->getUserId(InstituteList::BPI->value),
                'institution' => $this->getInstituteId(InstituteList::BPI->value),
                'title' => 'Upgrading the Biosafety System Through Enhanced Information Accessibility, and More Efficient and Streamlined Regulatory Process',
                'objective' => 'To improve implementation of Philippine biosafety regulatory system through an enhanced information accessibility, and more efficient and streamlined regulatory process',
                'expected_output' => 'Development of  plant breeding innovation online platform, conduct of trainings/workshops, creation of new biotech website, database for post-approval monitoring data, training material for public briefing',
                'project_leader' => $this->getLeader('Geronima Eusebio'),
                'funding_agency' => 'DA-BAR',
                'duration' => 'January 2021-December 2023',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'title' => 'Transcriptomic Analysis of Healthy and Cadang-cadang-infected Coconut Palms',
                'objective' => 'To determine differentially expressed genes during CCCVd infection',
                'expected_output' => 'Identify differentially expressed genes that could be rsponsible for the susceptibility or tolerance of coconut palm to cadang-cadang',
                'project_leader' => $this->getLeader('Maria Czet Fulleros'),
                'funding_agency' => 'DOST',
                'duration' => '3 years',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'title' => 'Application of Molecular techniques for the detection of coconust diseases and characterization of biological control agents of mahjor insect and pests of coconut',
                'objective' => 'To utilize molecular techniques for coconut disease diagnosis and characterization of insect pathogens of coconut pests, consequently increasing coconut yield and quality of marketbale coconut product through the development and establishment of sustainable pest and disease management',
                'expected_output' => 'Optimized a protocol for the detection of coconut pests',
                'project_leader' => $this->getLeader('Maria Leonila Imperial'),
                'funding_agency' => 'PCA-CRDP',
                'duration' => '3 years',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'title' => 'Sustainability of the Coconut Somatic Embryogeneis Tissue Cutlure Protocols for Mass Propagation and Multi-locational Testing of PCA recommended Plumule-derived varieties in Selected Provinces',
                'objective' => 'Regeneration and ex vitor establishment of plumule-derived plantlets from existing cultures. And develop an efficient and sustainbele nursery management protocol',
                'expected_output' => 'Mass propagate quality planting materials and improve efficiency of somatic embryo formation',
                'project_leader' => $this->getLeader('Cristeta A. Cueto'),
                'funding_agency' => 'PCA-CRDP',
                'duration' => '4.3 years',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'title' => 'Mass production of Embryo Cultured Makapuno Seedlings',
                'objective' => 'To mass produce Embryo culture Makapuno',
                'expected_output' => 'Embryo cultured Makapuno seedlings',
                'project_leader' => $this->getLeader('Maria Buena Areza-Ubaldo'),
                'funding_agency' => 'PCA-CRDP',
                'duration' => null,
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'title' => 'Optimization of Coconut Somatic Embryogenesis Protocols using coconut tissues of coconut',
                'objective' => 'To optimize clonal propagation protocol for coconut using inflorescense and unfertilized ovary',
                'expected_output' => 'Optimized culture medium for the mass propagation of coconut',
                'project_leader' => $this->getLeader('Maria Buena Areza-Ubaldo'),
                'funding_agency' => 'PCA-CRDP',
                'duration' => '3 years',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'title' => 'Genetic Fidelity Testing of Plumule-derives Coconut Plantlets',
                'objective' => null,
                'expected_output' => null,
                'project_leader' => null,
                'funding_agency' => 'PCA-CRDP',
                'duration' => '2 years',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'title' => 'Cadang-cadang Disease Management Program',
                'objective' => 'Identification of cadang-cadang free planting materials',
                'expected_output' => 'Indexed coconut planting amterials using MHA protocols',
                'project_leader' => $this->getLeader('Ma. Leonila Imperial'),
                'funding_agency' => 'PCA-CRDP',
                'duration' => null,
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PRRI->value),
                'institution' => $this->getInstituteId(InstituteList::PRRI->value),
                'title' => 'Characterization of Rubber Germplasm Collection',
                'objective' => '(1) To DNA-Fingerprint and  characterize rubber germplasm collection using SSR markers

(2) To identify rubber germplasm with novel genes and/ or QTLs important for rubber improvement;

(3) To assess the diversity of rubber genepool at PRRI genebank;

(4) Provide phenotypic and genotypic information of the rubber collections for utilization of end-users.',
                'expected_output' => '(1) Genetic Diversity of germplasm collection; (2) Genetic Identity of conserved clones; (3) Identify potential clones with economically important traits',
                'project_leader' => $this->getLeader('Jess Bryan M. Alvarino'),
                'funding_agency' => 'PRRI',
                'duration' => '2023-2024',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PRRI->value),
                'institution' => $this->getInstituteId(InstituteList::PRRI->value),
                'title' => 'In Silico Mining and Analysis of SSRs Derived from Rubber  EST Sequences',
                'objective' => '(1) To identify microsatellites from rubber ESTs available in  public databases.

(2) To design SSR primer pairs for the selected sequences.

 (3) To validate potential EST-SSRs.',
                'expected_output' => 'Identification and synthesis of functional  markers for Marker-assisted Breeding (MAS) in rubber',
                'project_leader' => $this->getLeader('Vivian M. Calambro'),
                'funding_agency' => 'PRRI',
                'duration' => '2023-2024',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'title' => 'Development of OPV Corn Varieties in Region 02',
                'objective' => 'Development of promising OPV corn for muti-location testing',
                'expected_output' => 'NSIC Registered OPV corn varieties for yellow, white flint and glutinous corn types',
                'project_leader' => $this->getLeader('Roynic Y. Aquino'),
                'funding_agency' => 'DA-BAR / Corn Program',
                'duration' => '2 years (2023-24)',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'title' => 'Development oImproved Composite Populations Towards Improved Traditional White Corn Varieties in Region 02',
                'objective' => 'Recombine improve native corn varieties for the development of improved composite populations',
                'expected_output' => 'Improved Native OPV corn',
                'project_leader' => $this->getLeader('Reymar Gulatera'),
                'funding_agency' => 'DA-BAR/ Corn Program',
                'duration' => '2023',
                'status' => 'Completed'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'title' => 'Upscaling of Technology Packages in the Upland area in Central Luzon',
                'objective' => 'To increase productivity and income of farmers and Indigenous People in the Upland areas in Central Luzon',
                'expected_output' => 'To double the Household income of IP farmers through production and Processing',
                'project_leader' => $this->getLeader('Emily A. Soriano & Veronica Mangune'),
                'funding_agency' => 'DA-BAR',
                'duration' => 'January2023',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'title' => 'Outscaling of Package of Technologies for Lowland Rice in Tiaong, Quezon',
                'objective' => 'To scale-out sustainable Package of Technologies and Agro-enterprise for rice production,for increased rice productivity and farmer\'s income.',
                'expected_output' => '15% increase in production and 50% increase in income',
                'project_leader' => $this->getLeader('Gina R. Rosarda'),
                'funding_agency' => 'DA RFO IV-A',
                'duration' => 'January 2023-December 2024',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'title' => 'Plant Pest and Disease Diagnosis',
                'objective' => 'To diagnose plant insect pests and diseases and provide control management through symptomatology, microscopy and LAMP technology for rice viral detection',
                'expected_output' => 'Information on plant pest diagnosis',
                'project_leader' => $this->getLeader('Ryan V. Rasgo'),
                'funding_agency' => 'Regular Fund from the Integrated Laboratory Division (ILD)',
                'duration' => 'Routine Activity of the RCPC',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'title' => 'Production of Biological Control Agents (Trichoderma - fungal antagonist) and Isaria spp (entomopathogenic fungus)',
                'objective' => 'To produce biological control agents as part of the control measures for major diseases and insect pests',
                'expected_output' => 'Trichoderma spp and Isaria spp',
                'project_leader' => $this->getLeader('Ryan V. Rasgo'),
                'funding_agency' => 'ILD and Banner Programs',
                'duration' => 'Routine Activity of the RCPC',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'title' => 'Production and Technology Promotion of Batuan (garcinia binucao) in Region VI',
                'objective' => 'The project aims to enhance income of farmers through utilization of batuan and upscale production of selected products and develop new products',
                'expected_output' => 'To enhance income by utilizing indigenous crops',
                'project_leader' => null,
                'funding_agency' => 'DA-BAR',
                'duration' => '2019',
                'status' => 'Completed'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIX->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIX->value),
                'title' => 'Yield Improvement of Upland Black Rice Cultivars in Zamboanga Peninsula',
                'objective' => 'To improve the yield performance of upland black rice up to 50% through marker-assisted breeding.',
                'expected_output' => 'Develop variety with a 50% yield increase in its current yield',
                'project_leader' => $this->getLeader('Ernie C. Camacho'),
                'funding_agency' => 'DA RFO 9 Research Division',
                'duration' => '6/1/2023',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIX->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIX->value),
                'title' => 'Development of Superhybrid Cacao Lines',
                'objective' => 'To develop F1 cacao hybrid lines with superior performance through marker-assisted selection',
                'expected_output' => 'F1 improved quality cacao lines',
                'project_leader' => $this->getLeader('Irene M. Dinoy'),
                'funding_agency' => 'DA RFO 9 Research Division',
                'duration' => '6/1/2023',
                'status' => 'Active'
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOX->value),
                'institution' => $this->getInstituteId(InstituteList::RFOX->value),
                'title' => 'Phenotyping and Genotyping of Potato Varities in Region 10 for Higher yield',
                'objective' => 'To enhance potato cultivation in Region 10 by conducting comprehensive phenotyping and genotyping, with a focus on utilizing biotechnology to identify and select diverse potato varieties that exhibit higher yield potential and adaptability to local environmental conditions.',
                'expected_output' => 'Assesed potato divesity and Identified potential varities for future breeding purposes through marker-assisted breeding',
                'project_leader' => $this->getLeader('Eleazer A. Jumalon'),
                'funding_agency' => 'Regular Funds/Agricultural Research',
                'duration' => '3 Years (2022-2024)',
                'status' => 'Active'
            ],

        ];

        foreach ($projects as $project) {
            TWGProject::create([
                'user_id' => $project['user_id'],
                'institution' => $project['institution'],
                'title' => $project['title'],
                'objective' => $project['objective'],
                'expected_output' => $project['expected_output'],
                'project_leader' => $project['project_leader'],
                'funding_agency' => $project['funding_agency'],
                'duration' => $project['duration'],
                'status' => $project['status']
            ]);
        }
    }

    private function insertProducts() {
        $products = [
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'name' => 'Molecular assays/techniques (MHA, PCR, Electrophoresis system (PAGE and AGE))',
                'brand' => 'Bio-Rad, Qiagen, CBS Scientific',
                'purpose' => 'For pathogen detection, gene expression analysis',
                'cost' => 'RT-PCR: 3M; PAGE/MHA: 500,000; AGE: 100,000',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'name' => 'Molecular assays/techniques (MHA, PCR, Electrophoresis system (PAGE and AGE))',
                'brand' => 'Bio-Rad, Qiagen, CBS Scientific',
                'purpose' => 'For pathogen detection and virulence testing',
                'cost' => 'Conventional PCR: 3M; PAGE: 500,000; AGE: 100,000',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'name' => 'NSIC Registered OPV corn varieties',
                'brand' => 'DA-CVRC, RFO 02',
                'purpose' => 'For food and feed ingredients',
                'cost' => '',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'name' => 'NSIC Registered OPV corn varieties',
                'brand' => 'DA-CVRC, RFO 02',
                'purpose' => 'For food',
                'cost' => '',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'name' => 'Mushroom crackers/ pellets',
                'brand' => 'DA-CLIARC Mushroom Technology and Development Center',
                'purpose' => 'Production input',
                'cost' => '80 Php/ 50g',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'name' => 'Trichoderma, Azospirilluim inoculant, Rhizobium inoculant',
                'brand' => 'DA 4A-Regional Soils Laboratory',
                'purpose' => 'Decomposer / Biofertilizer',
                'cost' => 'Php 150/liter',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'name' => 'Wood Vinegar and Biofertilizer',
                'brand' => 'DA-CARES',
                'purpose' => 'Production input',
                'cost' => '100 Php/L',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'name' => 'Embryo cultured Makapuno',
                'brand' => 'DA 4A-Lipa Agricultural Research and Experiment Station; DA 4A-Quezon Agricultural Research & Experiment Station',
                'purpose' => 'Production input/ planting material',
                'cost' => 'Php 500.00/seedling',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'name' => 'Tissue Cultured Lakatan Banana',
                'brand' => 'DA 4A-Lipa Agricultural Research and Experiment Station',
                'purpose' => 'Production input/ planting material',
                'cost' => 'Php 25.00/seedling',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'name' => 'Trichogramma chilonis/T. evanescens; Euborella sp.; Trichoderma harzianum; Beauveria bassiana',
                'brand' => 'DA 4A-Regional Crop Protection Center',
                'purpose' => 'Bio-control Agent',
                'cost' => 'Free of charge',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'name' => 'Information in the form of Test Reports',
                'brand' => 'N/A',
                'purpose' => '',
                'cost' => 'N/A',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'name' => 'Trichoderma spp and Isaria spp packets',
                'brand' => 'RCPC',
                'purpose' => 'For sustainable plant pest control',
                'cost' => 'N/A',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'name' => 'Batuan products jam, jelly, powder, sinigang mix, pastillas, pickles',
                'brand' => 'DA RFO 6 Research Division',
                'purpose' => 'Propagation and processing',
                'cost' => 'Jam, jelly - 180/200 ml bottle; pickles - 180/220ml bottles; powder - 280/100 grams',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOIX->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIX->value),
                'name' => 'UPLAND BLACK RICE',
                'brand' => 'DA RFO 9 Research Division',
                'purpose' => 'Varietal Development',
                'cost' => '',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::RFOX->value),
                'institution' => $this->getInstituteId(InstituteList::RFOX->value),
                'name' => 'Plant Tissue Culture Laboratory',
                'brand' => 'DA RFO 10, NMACLRC',
                'purpose' => 'For mass production of disease-free potato planting materials through in-vitro production. Source of various genotypes of potato',
                'cost' => '3 pesos per piece (G-zero)',
            ],
        ];

        foreach ($products as $product) {
            TWGProduct::create([
                'user_id' => $product['user_id'],
                'institution' => $product['institution'],
                'name' => $product['name'],
                'brand' => $product['brand'],
                'purpose' => $product['purpose'],
                'cost' => $product['cost'],
            ]);
        }
    }

    private function insertServices()
    {
        $services = [
            [
                'user_id' => $this->getUserId(InstituteList::BPI->value),
                'institution' => $this->getInstituteId(InstituteList::BPI->value),
                'type' => 'GM testing',
                'purpose' => 'Detection of GM material in agricultural crops',
                'direct_beneficiaries' => 'Technology developers',
                'indirect_beneficiaries' => 'Consumers',
                'officer_in_charge' => 'Geronima P. Eusebio',
                'cost' => '5,600 per sample',
            ],
            [
                'user_id' => $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'type' => 'Distribution of biological control agents to farmers',
                'purpose' => 'To disseminate biological control agents to coconut farms affected by coconut rhinoceros beetle, etc.',
                'direct_beneficiaries' => 'Farmers',
                'indirect_beneficiaries' => 'Researchers',
                'officer_in_charge' => 'Division Chief',
                'cost' => 'none',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::PCA->value),
                'institution' => $this->getInstituteId(InstituteList::PCA->value),
                'type' => 'Provide Makapuno seedlings',
                'purpose' => '',
                'direct_beneficiaries' => 'Farmers',
                'indirect_beneficiaries' => null,
                'officer_in_charge' => 'Department Manager',
                'cost' => '500/seedlings',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::PRRI->value),
                'institution' => $this->getInstituteId(InstituteList::PRRI->value),
                'type' => 'Research',
                'purpose' => 'Source of parental materials for rubber breeding and propagation of high-quality planting materials',
                'direct_beneficiaries' => 'Breeders, researchers and farmers',
                'indirect_beneficiaries' => $this->getInstituteId(InstituteList::RFOX->value),
                'officer_in_charge' => 'Honey Fe G. Boje',
                'cost' => '500,000',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::PRRI->value),
                'institution' => $this->getInstituteId(InstituteList::PRRI->value),
                'type' => 'Research',
                'purpose' => 'Available markers that can be used for screening clones for disease resistance, yield and authenticity',
                'direct_beneficiaries' => 'Breeders and researchers',
                'indirect_beneficiaries' => 'Farmers',
                'officer_in_charge' => 'Honey Fe G. Boje',
                'cost' => '420,000',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'type' => 'Research',
                'purpose' => 'Source of certified seeds for commercial production',
                'direct_beneficiaries' => 'Corn farmers, traders and processors',
                'indirect_beneficiaries' => null,
                'officer_in_charge' => 'Engr. Rolando D. Pedro ACC III/DA-CVRC Manager',
                'cost' => '5 Million',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'type' => 'Research',
                'purpose' => 'Source of certified seeds for commercial production',
                'direct_beneficiaries' => 'Corn farmers, traders and processors',
                'indirect_beneficiaries' => null,
                'officer_in_charge' => 'Engr. Rolando D. Pedro ACC III/DA-CVRC Manager',
                'cost' => '1.7 Million',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'type' => 'Plant Disease Diagnosis',
                'purpose' => 'Confirmatory Test, Early detection, Basis in the conduct of researches and Proper management recommendation to farmers (Food security)',
                'direct_beneficiaries' => 'Farmers',
                'indirect_beneficiaries' => 'Consumers',
                'officer_in_charge' => 'Minda Flor M. Aquino',
                'cost' => 'Free for farmers, Discounted for others',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOII->value),
                'institution' => $this->getInstituteId(InstituteList::RFOII->value),
                'type' => 'Training and extension',
                'purpose' => 'To capacitate farmers IPs and stakeholders to increase production and income.',
                'direct_beneficiaries' => 'Upland farmers',
                'indirect_beneficiaries' => 'Consumers and Entrepreneurs',
                'officer_in_charge' => 'Veronica P. Mangune',
                'cost' => 'Free of charge',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'type' => 'Training and extension - CFA production and application',
                'purpose' => 'To capacitate farmers and other stakeholders on the technology',
                'direct_beneficiaries' => 'Farmers, LGUs, private clienteles, students',
                'indirect_beneficiaries' => 'Consumers',
                'officer_in_charge' => 'Nora L. Talain',
                'cost' => 'Free of charge',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'type' => 'Training and Extension',
                'purpose' => 'To capacitate farmers and stakeholders to increase production and income.',
                'direct_beneficiaries' => 'Rice farmers',
                'indirect_beneficiaries' => 'Consumers',
                'officer_in_charge' => 'Felix Joselito Noceda, Agricultural Center Chief III',
                'cost' => 'Free of charge',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'type' => 'Training and extension - Embryo Culture of Makapuno',
                'purpose' => 'To capacitate farmers and other stakeholders on the technology',
                'direct_beneficiaries' => 'Farmers, LGUs, private clienteles, students',
                'indirect_beneficiaries' => 'Consumers, manufacturers, processors',
                'officer_in_charge' => 'Virgilia D. Arellano; Wilmer S. Faylon',
                'cost' => 'Free of charge',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOIVA->value),
                'institution' => $this->getInstituteId(InstituteList::RFOIVA->value),
                'type' => 'Training and extension - Tissue Culture of Lakatan',
                'purpose' => 'To capacitate farmers and other stakeholders on the technology',
                'direct_beneficiaries' => 'Farmers, LGUs, private clienteles, students',
                'indirect_beneficiaries' => 'Consumers, manufacturers, processors',
                'officer_in_charge' => 'Ma. Cecille Manzanilla',
                'cost' => 'Free of charge',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'type' => 'Plant Pest Diagnosis',
                'purpose' => 'To diagnose plant insect pests and diseases and provide control management through symptomatology, microscopy and LAMP technology for rice viral detection',
                'direct_beneficiaries' => 'Farmer partners, LGUs',
                'indirect_beneficiaries' => 'Consumers, Policy Makers',
                'officer_in_charge' => 'RYAN V. RASGO, Chief of Regional Crop Protection Center 6',
                'cost' => 'N/A',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'type' => 'Production of Trichoderma and Isaria',
                'purpose' => 'To produce biological control agents as part of the control measures for major diseases and insect pests',
                'direct_beneficiaries' => 'Farmer partners, LGUs',
                'indirect_beneficiaries' => 'Consumers, Policy Makers',
                'officer_in_charge' => 'RYAN V. RASGO, Chief of Regional Crop Protection Center 6',
                'cost' => 'N/A',
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOVI->value),
                'institution' => $this->getInstituteId(InstituteList::RFOVI->value),
                'type' => 'Trainings',
                'purpose' => 'To capacitate stakeholders on Batuan propagations and processing',
                'direct_beneficiaries' => 'Indigenous Farmers',
                'indirect_beneficiaries' => 'Consumers and Entrepreneurs',
                'officer_in_charge' => 'Primitiva B. Malaga - Agricultural Center Chief III & Nora T. Garpa - SRS II',
                'cost' => null,
            ],
            [
                'user_id' =>  $this->getUserId(InstituteList::RFOX->value),
                'institution' => $this->getInstituteId(InstituteList::RFOX->value),
                'type' => 'Plant Tissue Culture Laboratory',
                'purpose' => 'For mass production of disease-free potato planting materials through in-vitro production',
                'direct_beneficiaries' => 'Farmer associations, Individual farmers',
                'indirect_beneficiaries' => 'Research institutions, other interested clients',
                'officer_in_charge' => 'Lorena V. Duna - Chief, Research Division; Carmelito R. Lapoot - Chief, NMACLRC Station; Project Leaders: Milamar Aragua, Wendelyn Toraja, Eleazer Jumalon',
                'cost' => '4 Million',
            ],
        ];

        foreach ($services as $service) {
            TWGService::create([
                'user_id' => $service['user_id'],
                'institution' => $service['institution'],
                'type' => $service['type'],
                'purpose' => $service['purpose'],
                'direct_beneficiaries' => $service['direct_beneficiaries'],
                'indirect_beneficiaries' => $service['indirect_beneficiaries'],
                'officer_in_charge' => $service['officer_in_charge'],
                'cost' => $service['cost'],
            ]);
        }
    }

    private function getUserId($affiliationName)
    {
        return User::where('affiliation', Institute::where('name', $affiliationName)->first()->id)->first()->id;
    }

    private function getInstituteId($name)
    {
        return Institute::where('name', $name)->first()->id;
    }

    private function getLeader($name)
    {
        $model = TWGExpert::where('name', $name)->first();
        if ($model)
            return $model->id;
        return null;
    }
}

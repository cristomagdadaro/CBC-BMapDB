<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\TWGExpert;
use App\Models\TWGProject;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PermissionSeeder::class,
        ]);

        User::factory()->create([
            'fname' => 'Test',
            'lname' => 'User',
            'email' => 'sample@cbc.philrice.gov.ph',
        ]);
        User::factory(10)->create();

        \App\Models\TWGExpert::factory()->count(10)->create();
        \App\Models\TWGProject::factory()->count(20)->create();
        \App\Models\TWGProject::factory()->create([
            'twg_expert_id' => 1,
            'title' => 'Transcriptomic Analysis of Healthy and Cadang-cadang-infected Coconut Palms',
            'objective' => 'To determine differentially expressed genes during CCCVd infection',
            'expected_output' => 'Identify differentially expressed genes that could be rsponsible for the susceptibility or tolerance of coconut palm to cadang-cadang',
            'project_leader' => 'Maria Czet Fulleros',
            'funding_agency' => 'DOST',
            'duration' => '3 years',
            'status' => 'Active',
        ]);
        TWGProject::factory()->create([
            'twg_expert_id' => 1,
            'title' => 'Application of Molecular techniques for the detection of coconust diseases and characterization of biological control agents of mahjor insect and pests of coconut',
            'objective' => 'To utilize molecular techniques for coconut disease diagnosis and characterization of insect pathogens of coconut pests, consequently increasing coconut yield and quality of marketbale coconut product through the development and establishment of sustainable pest and disease management',
            'expected_output' => 'Optimized a protocol for the detection of coconut pests',
            'project_leader' => 'Maria Leonila Imperial',
            'funding_agency' => 'PCA-CRDP',
            'duration' => '3 years',
            'status' => 'Active',
        ]);
        TWGProject::factory()->create([
            'twg_expert_id' => 1,
            'title' => 'Sustainability of the Coconut Somatic Embryogeneis Tissue Cutlure Protocols for Mass Propagation and Multi-locational Testing of PCA recommended Plumule-derived varieties in Selected Provinces',
            'objective' => 'Regeneration and ex vitor establishment of plumule-derived plantlets from existing cultures. And develop an efficient and sustainbele nursery management protocol',
            'expected_output' => 'Mass propagate quality planting materials and improve efficiency of somatic embryo formation',
            'project_leader' => 'Cristeta A. Cueto',
            'funding_agency' => 'PCA-CRDP',
            'duration' => '4.3 years',
            'status' => 'Active',
        ]);
        TWGProject::factory()->create([
            'twg_expert_id' => 1,
            'title' => 'Mass production of Embryo Cultured Makapuno Seedlings',
            'objective' => 'To mass produce Embryo culture Makapuno',
            'expected_output' => 'Embryo cultured Makapuno seedlings',
            'project_leader' => 'Maria Buena Areza-Ubaldo',
            'funding_agency' => 'PCA-CRDP',
            'status' => 'Active',
        ]);
        TWGProject::factory()->create([
            'twg_expert_id' => 1,
            'title' => 'Optimization of Coconut Somatic Embryogenesis Protocols using coconut tissues of coconut',
            'objective' => 'To optimize clonal propagation protocol for coconut using inflorescense and unfertilized ovary',
            'expected_output' => 'Optimized culture medium for the mass propagation of coconut',
            'project_leader' => 'Maria Buena Areza-Ubaldo',
            'funding_agency' => 'PCA-CRDP',
            'duration' => '3 years',
            'status' => 'Active',
        ]);
        TWGProject::factory()->create([
            'twg_expert_id' => 1,
            'title' => 'Genetic Fidelity Testing of Plumule-derives Coconut Plantlets',
            'funding_agency' => 'PCA-CRDP',
            'duration' => '2 years',
            'status' => 'Active',
        ]);
        TWGProject::factory()->create([
            'twg_expert_id' => 1,
            'title' => 'Cadang-cadang Disease Management Program',
            'objective' => 'Identification of cadang-cadang free planting materials',
            'expected_output' => 'Indexed coconut planting amterials using MHA protocols',
            'project_leader' => 'Ma. Leonila Imperial',
            'funding_agency' => 'PCA-CRDP',
            'status' => 'Active',
        ]);

        \App\Models\TWGExpert::factory()->count(10)->create();
    }
}

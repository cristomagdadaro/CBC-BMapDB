<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commodity_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->unique()->constrained('commodities')->onDelete('cascade');

            // NSIC Registration
            $table->string('nsic_registration')->nullable()->comment('NSIC Registration');
            $table->string('nsic_registration_number')->nullable()->comment('NSIC Registration Number');
            $table->year('nsic_year_approved')->nullable()->comment('Year NSIC Registration Approved');

            // Plant Variety Protection
            $table->string('pvp_certificate_number')->nullable()->comment('PVP Certificate Number');
            $table->year('pvp_registration_year')->nullable()->comment('PVP Registration Year');

            // GM Regulatory Approval
            // NCBP
            $table->string('ncbp_project_type')->nullable()->comment('NCBP Project Type');
            $table->string('ncbp_status')->nullable()->comment('NCBP Status');
            $table->string('ncbp_supervising_ibc')->nullable()->comment('Supervising IBC');
            $table->string('ncbp_project_leaders')->nullable()->comment('NCBP Project Leaders');
            $table->date('ncbp_date_approval')->nullable()->comment('NCBP Date of Approval');
            $table->date('ncbp_date_completion')->nullable()->comment('NCBP Date of Completion');

            // AO8
            $table->string('ao8_transformation_event')->nullable()->comment('AO8 Transformation Event');
            $table->string('ao8_technology_developer')->nullable()->comment('AO8 Technology Developer');
            $table->string('ao8_direct_use_status')->nullable()->comment('AO8 Status of Application for Direct Use');
            $table->date('ao8_direct_use_date_applied')->nullable()->comment('AO8 Direct Use Date Applied');
            $table->date('ao8_direct_use_date_approved')->nullable()->comment('AO8 Direct Use Date Approved');
            $table->string('ao8_field_trial_status')->nullable()->comment('AO8 Status of Application for Field Trial');
            $table->date('ao8_field_trial_date_applied')->nullable()->comment('AO8 Field Trial Date Applied');
            $table->date('ao8_field_trial_date_approved')->nullable()->comment('AO8 Field Trial Date Approved');
            $table->string('ao8_propagation_status')->nullable()->comment('AO8 Status of Application for Propagation');
            $table->date('ao8_propagation_date_applied')->nullable()->comment('AO8 Propagation Date Applied');
            $table->date('ao8_propagation_date_approved')->nullable()->comment('AO8 Propagation Date Approved');

            // JDC 2016
            $table->string('jdc_2016_transformation_event')->nullable()->comment('JDC 2016 Transformation Event');
            $table->string('jdc_2016_technology_developer')->nullable()->comment('JDC 2016 Technology Developer');
            $table->string('jdc_2016_direct_use_status')->nullable()->comment('JDC 2016 Status of Application for Direct Use');
            $table->date('jdc_2016_direct_use_date_applied')->nullable()->comment('JDC 2016 Direct Use Date Applied');
            $table->date('jdc_2016_direct_use_date_approved')->nullable()->comment('JDC 2016 Direct Use Date Approved');
            $table->string('jdc_2016_ffp_status')->nullable()->comment('JDC 2016 Status of Application for Renewal (FFP)');
            $table->date('jdc_2016_ffp_date_applied')->nullable()->comment('JDC 2016 FFP Date Applied');
            $table->date('jdc_2016_ffp_date_approved')->nullable()->comment('JDC 2016 FFP Date Approved');
            $table->string('jdc_2016_field_trial_status')->nullable()->comment('JDC 2016 Status of Application for Field Trial');
            $table->date('jdc_2016_field_trial_date_applied')->nullable()->comment('JDC 2016 Field Trial Date Applied');
            $table->date('jdc_2016_field_trial_date_approved')->nullable()->comment('JDC 2016 Field Trial Date Approved');
            $table->string('jdc_2016_propagation_status')->nullable()->comment('JDC 2016 Status of Application for Propagation');
            $table->date('jdc_2016_propagation_date_applied')->nullable()->comment('JDC 2016 Propagation Date Applied');
            $table->date('jdc_2016_propagation_date_approved')->nullable()->comment('JDC 2016 Propagation Date Approved');
            $table->string('jdc_2016_renewal_propagation_status')->nullable()->comment('JDC 2016 Status of Renewal (Propagation)');
            $table->date('jdc_2016_renewal_propagation_date_applied')->nullable()->comment('JDC 2016 Renewal Propagation Date Applied');
            $table->date('jdc_2016_renewal_propagation_date_approved')->nullable()->comment('JDC 2016 Renewal Propagation Date Approved');
            $table->string('jdc_2016_deregulation_status')->nullable()->comment('JDC 2016 Status of Petition for Deregulation');
            $table->date('jdc_2016_deregulation_date_applied')->nullable()->comment('JDC 2016 Deregulation Date Applied');
            $table->date('jdc_2016_deregulation_date_approved')->nullable()->comment('JDC 2016 Deregulation Date Approved');

            // JDC 2021
            $table->string('jdc_2021_transformation_event')->nullable()->comment('JDC 2021 Transformation Event');
            $table->string('jdc_2021_technology_developer')->nullable()->comment('JDC 2021 Technology Developer');
            $table->string('jdc_2021_direct_use_status')->nullable()->comment('JDC 2021 Status of Application for Direct Use');
            $table->date('jdc_2021_direct_use_date_applied')->nullable()->comment('JDC 2021 Direct Use Date Applied');
            $table->date('jdc_2021_direct_use_date_approved')->nullable()->comment('JDC 2021 Direct Use Date Approved');
            $table->string('jdc_2021_field_trial_status')->nullable()->comment('JDC 2021 Status of Application for Field Trial');
            $table->date('jdc_2021_field_trial_date_applied')->nullable()->comment('JDC 2021 Field Trial Date Applied');
            $table->date('jdc_2021_field_trial_date_approved')->nullable()->comment('JDC 2021 Field Trial Date Approved');
            $table->string('jdc_2021_propagation_status')->nullable()->comment('JDC 2021 Status of Application for Propagation');
            $table->date('jdc_2021_propagation_date_applied')->nullable()->comment('JDC 2021 Propagation Date Applied');
            $table->date('jdc_2021_propagation_date_approved')->nullable()->comment('JDC 2021 Propagation Date Approved');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_info');
    }
};

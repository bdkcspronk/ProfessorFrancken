<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAssociationActivitiesSignUpsTable extends Migration
{
    /**
     * Whether a sign up has a place, has been offered a place that still has
     * to be accepted, or is on the waiting list. Every existing sign up has a place.
     */
    public function up() : void
    {
        Schema::table('association_activities_sign_ups', function (Blueprint $table) : void {
            $table->string('status', 16)->default('placed')->after('plus_ones');
        });
    }

    public function down() : void
    {
        Schema::table('association_activities_sign_ups', function (Blueprint $table) : void {
            $table->dropColumn('status');
        });
    }
}

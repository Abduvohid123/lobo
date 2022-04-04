<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclarantsDescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('declarants_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('description');
            $table->timestamps();
        });
    }
}

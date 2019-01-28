<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcommerceUspsConfiguspsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icommerceusps__configusps', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // Your fields
            $table->string('user_id');
            $table->string('zip_origin');
            $table->tinyInteger('shipping_rates')->default(0)->unsigned();//0 Online - All 1
            $table->tinyInteger('size')->default(0)->unsigned();//0 Regular - 1 Large
            $table->tinyInteger('machinable')->default(1)->unsigned();//0 NO - 1 YES

            $table->text('options')->default('')->nullable();
            $table->tinyInteger('status')->default(0)->unsigned();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icommerceusps__configusps');
    }
}

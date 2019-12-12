<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('music_id')->unsigned()->index()->comment('音楽ID');
            $table->bigInteger('artist_id')->unsigned()->index()->comment('アーティストID');
            $table->string('artist_name')->comment('アーティスト名(表示名)');
            $table->string('part_name')->comment('パート');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
        });

        \DB::statement('ALTER TABLE `parts` ADD FULLTEXT INDEX ft_artist_name (`artist_name`) WITH PARSER ngram');
        \DB::statement('ALTER TABLE `parts` ADD FULLTEXT INDEX ft_part_name (`part_name`) WITH PARSER ngram');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts');
    }
}

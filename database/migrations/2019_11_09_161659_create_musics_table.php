<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('album_id')->unsigned()->index()->comment('アルバムID');
            $table->string('title')->comment('曲名');
            $table->integer('track_no')->unsigned()->comment('トラックNO');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
        });

        \DB::statement('ALTER TABLE `musics` ADD FULLTEXT INDEX ft_title (`title`) WITH PARSER ngram');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musics');
    }
}

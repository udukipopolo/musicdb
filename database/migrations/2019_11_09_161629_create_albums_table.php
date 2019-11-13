<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('アルバムタイトル');
            $table->bigInteger('artist_id')->unsigned()->index()->comment('アーティストID');
            $table->string('artist_name')->comment('アーティスト名');
            $table->text('description')->comment('詳細・アルバムスタッフ等');
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
        Schema::dropIfExists('albums');
    }
}

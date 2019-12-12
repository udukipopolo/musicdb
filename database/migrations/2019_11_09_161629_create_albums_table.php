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
            $table->string('affi_apple_music', 1000)->nullable()->comment('apple music アフィリエイト	');
            $table->string('affi_amazon', 1000)->nullable()->comment('amazonアフィリエイト');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
        });

        \DB::statement('ALTER TABLE `albums` ADD FULLTEXT INDEX ft_title (`title`) WITH PARSER ngram');
        \DB::statement('ALTER TABLE `albums` ADD FULLTEXT INDEX ft_artist_name (`artist_name`) WITH PARSER ngram');

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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocaleNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locale_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('localable_id')->unsigned();
            $table->string('localable_type');
            $table->string('locale', 2)->default('ja')->comment('言語');
            $table->string('column')->comment('項目');
            $table->bigInteger('artist_id')->unsigned()->nullable()->comment('アーティスト・別名義の場合のみ');
            $table->string('name')->comment('名称');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->index(['localable_id', 'localable_type', 'column', 'locale'], 'column_index');
        });
        \DB::statement('ALTER TABLE `locale_names` ADD FULLTEXT INDEX ft_name (`name`) WITH PARSER ngram');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locale_names');
    }
}

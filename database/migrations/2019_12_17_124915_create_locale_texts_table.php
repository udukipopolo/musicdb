<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocaleTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locale_texts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('localable_id')->unsigned();
            $table->string('localable_type');
            $table->string('locale', 2)->default('ja')->comment('言語');
            $table->string('column')->comment('項目');
            $table->text('text')->comment('テキスト');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->index(['localable_id', 'localable_type', 'column', 'locale'], 'column_index');
        });
        \DB::statement('ALTER TABLE `locale_texts` ADD FULLTEXT INDEX ft_text (`text`) WITH PARSER ngram');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locale_texts');
    }
}

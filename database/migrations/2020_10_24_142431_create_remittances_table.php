<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemittancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remittances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payer_id')->unsigned()->index()
                ->comment('Плательщик');
            $table->bigInteger('recipient_id')->unsigned()->index()
                ->comment('Получатель');
            $table->float('value')->default(0)
                ->comment('Количество переводимых средств');
            $table->timestamps();
            $table->timestamp('do_at')
                ->comment('Выполнение запланировано на это время');
            $table->boolean('done')
                ->default(0)
                ->comment('Платеж проведен');

            $table->foreign('payer_id')->references('id')->on('users');
            $table->foreign('recipient_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remittances');
    }
}

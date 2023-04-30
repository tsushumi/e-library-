<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->string('slug')->unique();
            $table->string('publisher')->nullable();
            $table->string('ISBN_10')->unique();
            $table->string('ISBN_13')->unique();
            $table->integer('edition')->nullable();
            $table->decimal('value', 8, 2)->nullable();
            $table->integer('copies')->default(1);
            $table->tinyInteger('borrowed_books')->default(0);
            $table->boolean('bookable')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('books');
    }
};

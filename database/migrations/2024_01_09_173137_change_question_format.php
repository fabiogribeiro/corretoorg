<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Question;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function(Blueprint $table) {
            $table->jsonb('answer_data')
                ->default(new Expression(
                        '\'{"type": "multiple-choice", "answer": "A", "options": ["A", "B", "C", "D"]}\'::json'
                    )
                );
        });

        foreach (Question::all() as $q) {
            $q->answer_data['type'] = $q->type;
            $q->answer_data['answer'] = $q->answer;
            $q->save();
        }

        Schema::table('questions', function(Blueprint $table) {
            $table->dropColumn('answer');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations. Garbage, don't use.
     */
    public function down(): void
    {
         Schema::table('questions', function(Blueprint $table) {
            $table->string('type')->default('multiple-choice');
            $table->string('answer');
            $table->dropColumn('answer_data');
        });
    }
};

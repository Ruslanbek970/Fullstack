<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('banned_at')->nullable()->after('remember_token');
        });

        Schema::table('memes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('status', 32)->default('pending')->after('image');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('published_at')->nullable()->after('rejection_reason');
        });

        Schema::create('meme_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meme_id')->constrained('memes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['meme_id', 'user_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meme_id')->constrained('memes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });

        $firstUserId = DB::table('users')->orderBy('id')->value('id');
        if ($firstUserId) {
            DB::table('memes')->whereNull('user_id')->update([
                'user_id' => $firstUserId,
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        Schema::table('memes', function (Blueprint $table) {
            $table->dropColumn('likes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('meme_likes');

        Schema::table('memes', function (Blueprint $table) {
            $table->unsignedInteger('likes')->default(0)->after('image');
        });

        Schema::table('memes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status', 'rejection_reason', 'published_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('banned_at');
        });
    }
};

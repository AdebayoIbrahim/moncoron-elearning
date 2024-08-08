<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDawahPostsTableStructureV2 extends Migration
{
    public function up()
    {
        Schema::table('dawah_posts', function (Blueprint $table) {
            // Check and update columns if needed
            if (!Schema::hasColumn('dawah_posts', 'dawah_id')) {
                $table->bigInteger('dawah_id')->unsigned()->nullable()->after('id');
            }

            if (!Schema::hasColumn('dawah_posts', 'user_id')) {
                $table->bigInteger('user_id')->unsigned()->change();
            }

            if (!Schema::hasColumn('dawah_posts', 'title')) {
                $table->string('title')->change();
            }

            if (!Schema::hasColumn('dawah_posts', 'content')) {
                $table->text('content')->nullable()->change();
            }

            if (!Schema::hasColumn('dawah_posts', 'type')) {
                $table->string('type')->default('text')->change();
            }

            if (!Schema::hasColumn('dawah_posts', 'attachment')) {
                $table->string('attachment')->nullable()->change();
            }

            // Add timestamps if they do not exist
            if (!Schema::hasColumn('dawah_posts', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('dawah_posts', function (Blueprint $table) {
            // Revert changes
            $table->dropColumn(['dawah_id', 'user_id', 'title', 'content', 'type', 'attachment']);
            
            // Drop timestamps if they exist
            if (Schema::hasColumn('dawah_posts', 'created_at')) {
                $table->dropTimestamps();
            }
        });
    }
}

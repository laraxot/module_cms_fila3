<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Class CreatePagesTable.
 */
class CreatePagesTable extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            function (Blueprint $table): void {
                $table->id();
                $table->string('title')->index();
                $table->string('slug')->unique();
                $table->string('layout')->default('default')->index();
                $table->json('blocks');
                $table->foreignId('parent_id')->nullable()->constrained('pages')->cascadeOnDelete()->cascadeOnUpdate();
                $table->timestamps();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                if (! $this->hasColumn('tenant_name')) {
                   $table->string('tenant_name')->nullable();
                }
                // if (! $this->hasColumn('profile_photo_path')) {
                //    $table->string('profile_photo_path', 2048)->nullable();
                // }
            }
        );
    }
}

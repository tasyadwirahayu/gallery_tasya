<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('nama_lengkap', 225)->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('no_telephone')->unique()->nullable();
            $table->text('alamat')->nullable();
            $table->text('bio')->nullable();
            $table->enum('status_user', ['Aktif', 'NonAktif'])->default('Aktif');
            $table->enum('role', ['user', 'admin',])->default('user');
            $table->string('foto_profil')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        // TRIGGER REKAP INSERT USER
        DB::unprepared('
        CREATE TRIGGER trigger_user_insert
        AFTER INSERT ON users
        FOR EACH ROW
        BEGIN
            DECLARE alamat_value VARCHAR(255);

            -- Set alamat_value ke nilai alamat dari NEW atau KOSONGIN jika NULL
            SET alamat_value = COALESCE(NEW.alamat, "");

            INSERT INTO log_users (user_id, action, username, email, alamat, no_telephone, keterangan, created_at, updated_at)
            VALUES (NEW.id, "INSERT", NEW.username, NEW.email, alamat_value, NEW.no_telephone, "", NOW(), NOW());
        END;
    ');
    //TRIGGER REKAP UPDATE USER
    DB::unprepared('
    CREATE TRIGGER trigger_user_update
    AFTER UPDATE ON users
    FOR EACH ROW
    BEGIN
        INSERT INTO log_users (user_id, action, username, email, alamat, no_telephone, keterangan, created_at, updated_at)
        VALUES (NEW.id, "UPDATE", NEW.username, NEW.email, NEW.alamat, NEW.no_telephone, "", NOW(), NOW());
    END;
');
//TRIGGER REKAP DELETE USER
DB::unprepared('
CREATE TRIGGER trigger_user_delete
AFTER DELETE ON users
FOR EACH ROW
BEGIN
    INSERT INTO log_users (user_id, action, username, email, alamat, no_telephone, keterangan, created_at, updated_at)
    VALUES (OLD.id, "DELETE", OLD.username, OLD.email, OLD.alamat, OLD.no_telephone, "", NOW(), NOW());
END;
');
  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

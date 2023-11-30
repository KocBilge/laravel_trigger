<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateUserNotificationsTrigger extends Migration {
    public function up() {
        DB::unprepared('
            CREATE TRIGGER after_user_insert
            AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO user_notifications (user_id, message, created_at, updated_at)
                VALUES (NEW.id, CONCAT("New user registered: ", NEW.username), NOW(), NOW());
            END
        ');
    }

    public function down() {
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_insert');
    }
}

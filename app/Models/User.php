<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// User Model
class User extends Model {
    protected $fillable = ['username'];

    protected static function boot() {
        parent::boot();

        // Actions to be performed when the user is registered
        static::created(function ($user) {
            // Sending notifications
            event(new UserRegistered($user));
        });
    }
}

// UserRegistered event
class UserRegistered implements ShouldBroadcast {
    public $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function broadcastOn() {
        return new Channel('user.' . $this->user->id);
    }
}

// Event - Listener
class UserRegisteredListener {
    public function handle(UserRegistered $event) {
        // Sending emails or other notifications
        $username = $event->user->username;
        echo "Email notification sent: New user registered - $username\n";
    }
}

// Register event listener with Laravel service provider
class AppServiceProvider extends ServiceProvider {
    public function boot() {
        Event::listen(UserRegistered::class, UserRegisteredListener::class);
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
      use HasApiTokens, Notifiable;
    /**
     * Get the decrypted mobile number.
     *
     * @return string
     */
    protected $connection = "mysql_master";

    // Accessor for the email attribute
    public function getMobileAttribute()
    {
        $mobile = $this->attributes['mobile'] ?? null;

        if (!$mobile) {
            return null;
        }
        try {
            return base64_decode(base64_decode($mobile));
        } catch (\Exception $e) {
            return $mobile;
        }
    }

    public function getEmailAttribute()
    {
        return $this->decryptAttribute('email');
    }

    public function getEmergencyMobileAttribute()
    {
        return $this->decryptAttribute('emergency_mobile');
    }
    // Accessor for the adharid attribute
    public function getAdharidAttribute()
    {
        return $this->decryptAttribute('adharid');
    }

    // Accessor for the panid attribute
    public function getPanidAttribute()
    {
        return $this->decryptAttribute('panid');
    }

    /**
     * Decrypts an attribute value with proper handling.
     *
     * @param string $attribute
     * @return mixed
     */
    protected function decryptAttribute($attribute)
    {
        if (isset($this->attributes[$attribute])) {
            try {
                return Crypt::decrypt($this->attributes[$attribute]);
            } catch (DecryptException $e) {
                // Log the decryption error for debugging purposes
                \Log::error('Failed to decrypt attribute: ' . $attribute, ['error' => $e->getMessage()]);
                return $this->attributes[$attribute]; // Return null if decryption fails
            }
        }

        return null; // Return null if attribute is not set
    }

    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('d-m-Y / H:i:s');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'pincode',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

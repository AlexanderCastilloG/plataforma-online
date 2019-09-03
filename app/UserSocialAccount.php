<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserSocialAccount
 *
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSocialAccount query()
 * @mixin \Eloquent
 */
class UserSocialAccount extends Model
{
    // que datos se pueden insertar
    protected $fillable = ['user_id', 'provider', 'provider_uid'];

    // nuestros modelos ya no va buscar las columnas de updated_at, created_at
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use App\Model\Activation;
use App\Model\Address;
use App\Model\Article;
use App\Model\AttributeType;
use App\Model\AttributeTypeValue;
use App\Model\Auction;
use App\Model\AuctionResult;
use App\Model\Discount;
use App\Model\favorite;
use App\Model\Order;
use App\Model\Payment;
use App\Model\Product;
use App\Model\Requestgift;
use App\Model\Role;
use App\Model\Suggestion;
use App\Model\Variation;
use App\Model\Category;
use App\Traits\HasComment;
use App\Traits\HasDiscount;
use App\Traits\HasImage;
use App\Utility\Level;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Model\Rating;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /**
     * فقط ادمین و سوپرادمین اجازه‌ی ورود به پنل مدیریت Filament (/admin) را دارند.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isSuperAdminOrAdmin();
    }

    use Notifiable, SoftDeletes, HasImage, HasComment, HasDiscount, AuthenticationLogable;

    public static $preventAttrSet = false;
    protected $appends = ['fullName'];
    protected $fillable = [
        'active',
        'block',
        'level',
        'name',
        'family',
        'email',
        'mobile',
        'tell',
        'national_code',
        'economic_code',
        'full_address',
        'age',
        'discount_percent',
        'sex',
        'password',
        'wallet',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getFullNameAttribute()
    {
        return $this->name . " " . $this->family;
    }

    public function scopeAdminRole($query)
    {
        if (auth()->user()->isSuperAdmin()) {
            return $query;
        } elseif (auth()->user()->isAdmin()) {
            return $query->where('id', ">", 1);
        }
    }


    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) { // before delete() method call this
            $user->article()->delete();
            $user->address()->delete();
            $user->products()->delete();
            $user->variations()->delete();
            $user->discount()->delete();
            $user->discounts()->delete();
            $user->comments()->delete();
            $user->image()->delete();
            // do the rest of the cleanup...
        });
    }

    public function requestgifts()
    {
        return $this->hasMany(Requestgift::class);
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }


    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return !!$role->intersect($this->roles)->count();
    }

    public function isCustomer()
    {
        return $this->level == Level::USER ? true : false;
    }

    public function isColleague()
    {
        return $this->level == Level::COLLEAGUE ? true : false;
    }

    public function isAdmin()
    {
        return $this->level == Level::ADMIN || $this->level == Level::OPERATOR ? true : false;
    }

    public function isOperator()
    {
        return $this->level == Level::OPERATOR ? true : false;
    }


    public function isSuperAdmin()
    {
        return $this->level == Level::SUPER_ADMIN ? true : false;
    }

    public function isSuperAdminOrAdmin()
    {
        return $this->level == Level::SUPER_ADMIN || $this->level == Level::ADMIN ? true : false;
    }

    public function isAdminOrSuperAdmin()
    {
        return $this->level == Level::SUPER_ADMIN || $this->level == Level::ADMIN ? true : false;
    }
    public function isSeller()
    {
        return $this->level == Level::SELLER ? true : false;
    }


    public function article()
    {
        return $this->hasMany(Article::class);
    }

    /*order*/
    public function order()
    {
        return $this->hasMany(Order::class);
    }

    /*payment*/
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }


    public function address()
    {
        return $this->hasMany(Address::class);
    }


    /* owner */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function scopeOperator($query)
    {
        if (auth()->user()->isSuperAdminOrAdmin()) {
            return $query;
        } else {
            return $query->whereNotIn('level', [Level::SUPER_ADMIN, Level::ADMIN, Level::OPERATOR]);
        }
    }


    /* relation with attribute type */
    public function attributeType()
    {
        return $this->hasMany(AttributeType::class);
    }

    /* relation with attribute type value */
    public function attributeTypeValue()
    {
        return $this->hasMany(AttributeTypeValue::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function activations()
    {
        return $this->hasMany(Activation::class);
    }

    public function suggestion()
    {
        return $this->hasMany(Suggestion::class);
    }


    /* is_credit */
    public static function is_credit($user_id)
    {
        $user = static::whereActive(1)->findOrFail($user_id);
        return $user;
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function path()
    {
        return Url('seller/' . $this->id);
    }

    public function userDiscount()
    {
        return $this->belongsToMany(Discount::class);
    }


    public function auctions()
    {
        return $this->belongsToMany(Auction::class);
    }

    public function production()
    {
        return $this->belongsToMany(Product::class);
    }


    public function auctionResult()
    {
        return $this->hasMany(AuctionResult::class);
    }

    public function rates()
    {
        return $this->hasMany(Rating::class);
    }

    public function favorites()
    {
        return $this->hasMany(favorite::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}

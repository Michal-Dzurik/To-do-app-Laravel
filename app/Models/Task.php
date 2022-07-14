<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $description
 * @property int $done
 * @property int $deleted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $category
 * @property-read mixed $shared
 * @method static Builder|Task description($description)
 * @method static Builder|Task done($done)
 * @method static Builder|Task title($title)
 * @method static Builder|Task undeleted()
 * @method static Builder|Task whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|Task onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Task withoutTrashed()
 */
class Task extends Model
{
    use HasFactory,SoftDeletes;

    /**
     *  Attributes I can create
     *
     * @var array<string>
     */
    protected $appends = ['shared'];

    /**
     *  Allowed attributes for filtering
     *
     * @var array<string>
     */
    protected $allowedFilterFields = ['title','description','shared','category','done','created_at','updated_at'];

    /**
     * All events Task can be observed on
     *
     * @var array<string>
     */
    protected $observables = ['shared','unshared','done','undone'];

    /**
     * Data for observer
     *
     * @var array<string,array>
     */
    public $eventData = [];

    /**
     * All attributes that can be inserted to the database
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'done',
        'category',
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

    //Events
    /**
     * Triggers done event.
     *
     * @param  User  $user
     * @return void
     */
    public function makeDone($user){
        $this->fireModelEvent('done',true,[$user]);
    }

    /**
     * Triggers undone event.
     *
     * @param  User  $user
     * @return void
     */
    public function makeUndone($user){
        $this->fireModelEvent('undone',true,[$user]);
    }

    /**
     * Triggers share event.
     *
     * @param  User  $user
     * @param  User  $action_user
     * @return void
     */
    public function makeShared($user,$action_user){
        $this->fireModelEvent('shared',true,[$user,$action_user]);
    }

    /**
     * Triggers unshare event.
     *
     * @param  User  $user
     * @param  User  $action_user
     * @return void
     */
    public function makeUnshared($user,$action_user){
        $this->fireModelEvent('unshared',true,[$user,$action_user]);
    }

    /**
     * Triggers events but add data for it to model.
     *
     * @param  string  $event
     * @param  boolean  $halt
     * @param  array  $data
     * @return void
     */
    protected function fireModelEvent($event, $halt = true, array $data = []) {
        $this->eventData[$event] = $data;
        return parent::fireModelEvent($event, $halt);
    }

    /**
     * Returns data for a certain event.
     *
     * @param  string  $event
     *
     * @return array|null
     */
    public function getEventData(string $event) {
        if (array_key_exists($event, $this->eventData)) {
            return $this->eventData[$event];
        }

        return NULL;
    }

    // Modifiers
    /**
     * Modifier for share attribute.
     *
     * @return boolean|null
     */
    public function getSharedAttribute()
    {
        if ($this->pivot){
            $this->makeHidden('pivot');
            return $this->pivot->shared;
        }

        return null;
    }

    // Relations
    public function users(){
        return $this->belongsToMany(User::class,'task_user')->withPivot('shared');
    }

    // Scopes

    /**
     * Adds where to query.
     *
     * @return boolean|null
     */
    public function scopeTitle($query, $title)
    {
        if (!is_null($title)) {
            return $query->where('title', 'like', '%'.$title.'%');
        }

        return $query;
    }

    /**
     * Adds where to query.
     *
     * @return boolean|null
     */
    public function scopeDescription($query, $description)
    {
        if (!is_null($description)) {
            return $query->where('description', 'like', '%'.$description.'%');
        }

        return $query;
    }

    /**
     * Adds where to query.
     *
     * @return boolean|null
     */
    public function scopeDone($query, $done)
    {
        if (!is_null($done)) {
            return $query->where('done', 'like', '%'.$done.'%');
        }

        return $query;
    }

}

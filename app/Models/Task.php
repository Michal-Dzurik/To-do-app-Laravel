<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;

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
 */
class Task extends Model
{
    use HasFactory;

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
    protected $allowedFilterFields = ['title','description','shared','category','done','undeleted','created_at','updated_at'];

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
        'deleted',
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
        'deleted'
    ];

    //Events
    public function makeDone($user){
        $this->fireModelEvent('done',true,[$user]);
    }

    public function makeUndone($user){
        $this->fireModelEvent('undone',true,[$user]);
    }

    public function makeShared($user,$action_user){
        $this->fireModelEvent('shared',true,[$user,$action_user]);
    }

    public function makeUnshared($user,$action_user){
        $this->fireModelEvent('unshared',true,[$user,$action_user]);
    }

    protected function fireModelEvent($event, $halt = true, array $data = []) {
        $this->eventData[$event] = $data;
        return parent::fireModelEvent($event, $halt);
    }

    public function getEventData(string $event) {
        if (array_key_exists($event, $this->eventData)) {
            return $this->eventData[$event];
        }

        return NULL;
    }

    // Modifiers
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
    public static function scopeUndeleted($query){
        return $query->where(['deleted' => 0]);
    }


    public function scopeTitle($query, $title)
    {
        if (!is_null($title)) {
            return $query->where('title', 'like', '%'.$title.'%');
        }

        return $query;
    }

    public function scopeDescription($query, $description)
    {
        if (!is_null($description)) {
            return $query->where('description', 'like', '%'.$description.'%');
        }

        return $query;
    }

    public function scopeDone($query, $done)
    {
        if (!is_null($done)) {
            return $query->where('done', 'like', '%'.$done.'%');
        }

        return $query;
    }

}

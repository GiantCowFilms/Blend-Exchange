<?php declare(strict_types = 1);

/**
 * BlendFile Class
 *
 */

namespace BlendExchange\Blend\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use BlendExchange\Model\Traits\RandomId;
use BlendExchange\Access\Model\Access;
use BlendExchange\Flag\Model\Flag;

class BlendFile extends Eloquent
{
    use RandomId;

    protected function idSize()
    {
        return 8;
    }
    public $incrementing = false;
    /**
     * Setup soft deletes
     */
    
    use \Webkid\LaravelBooleanSoftdeletes\SoftDeletesBoolean;

    const IS_DELETED = 'deleted';

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'blends';

    public $timestamps = false;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [

        'questionLink',

    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */

    protected $visible = [

        'questionLink',
        'id',
        'accesses',
        'views_count',
        'downloads_count',
        'favorites_count',
        'flags',
        'fileName',
        'fileSize',
        'adminComment'
    ];

    public function accesses()
    {
        return $this->hasMany(Access::class, 'fileId', 'id');
    }

    public function views()
    {
        return $this->accesses()->where('type', 'view')->groupBy('ip');
    }

    public function downloads()
    {
        return $this->accesses()->where('type', 'download')->groupBy('ip');
    }

    public function favorites()
    {
        return $this->accesses()->where('type', 'favorite');
    }

    public function flags()
    {
        return $this->hasMany(Flag::class, 'fileId', 'id');
    }

    /**
     * Checks if the file is visible to the public
     *
     * @return boolean
     */
    public function isVisible(): bool
    {
        return 
        $this->isDownloadable() &&
        (!$this->flags()->where('offense','copyright')->exists());
    }

    /**
     * Checks if the binary file exists. Only returns false if attempting to download would produce an error (file has been deleted, etc.)
     *
     * @return boolean
     */
    public function isDownloadable() : bool
    {
        return $this->fileGoogleId !== null;
    }

    // SELECT `blends`.*, COUNT(DISTINCT `views`.`ip`)as vc, COUNT(DISTINCT `downloads`.`ip`) as dc FROM `blends`
    // LEFT JOIN `accesses` as `downloads` ON `downloads`.`fileId` = `blends`.`id` AND `downloads`.`type`='download'
    // LEFT JOIN `accesses` as `views` ON `views`.`fileId` = `blends`.`id` AND `views`.`type` = 'view'
    // //WHERE `blends`.`id` = '0V8B0vVR'
    // GROUP BY `blends`.`id`  
    // ORDER BY `blends`.`legacy_id` ASC

    //Could be worse...
    public function scopeWithUniqueCountLegacy ($query,$relation,$field) {
        $db = $this->getConnection();
        $foreign_table = $this->$relation()->getRelated()->getTable();
        $foreign_key = $this->$relation()->getForeignKeyName();

        $local_table = $this->getTable();
        $local_key = $this->getKeyName();
        $query->addSelect($db->raw('`blends`.*'));
        $query->addSelect($db->raw('COUNT(DISTINCT `'.$relation.'`.`'.$field.'` ) as ' . $relation.'_count'))
        ->leftJoin($foreign_table . ' as ' . $relation, function ($join) use($foreign_table,$foreign_key,$local_table,$local_key,$relation) {

            $relation_query = $this->$relation()->getQuery();
            // Qualify where clauses with table name to avoid ambiguity
            $whereClauses = array_slice($relation_query->getQuery()->wheres,2);
            foreach($whereClauses as $key => $whereClause)
            {
                $whereClauses[$key]['column'] = $relation . '.' .$whereClause['column'];
            }

            $join->on($local_table . '.' .$local_key, '=', $relation . '.' . $foreign_key)->mergeWheres($whereClauses,$relation_query->getQuery()->bindings);
        });
        $query->groupBy('blends.id');
        return $query;
    }

    
    // SELECT `blends`.*, (SELECT COUNT(DISTINCT `ip`) FROM `accesses` WHERE `type`='view' AND `fileId`=`blends`.`id`) as vc, COUNT(DISTINCT `downloads`.`ip`) as dc FROM `blends` 
    // GROUP BY `blends`.`id`  

    public function scopeWithUniqueCount ($query,$relation,$field) {
        $db = $this->getConnection();
        $foreign_table = $this->$relation()->getRelated()->getTable();

        $relation_query = $this->$relation()->getQuery();

        $whereClauses = array_slice($relation_query->getQuery()->wheres,2);
        $subQuery = $db->table($foreign_table)->select($db->raw("COUNT(DISTINCT `{$field}`)"))->whereRaw('`fileId`=`blends`.`id`');
        $subQuery->mergeWheres($whereClauses,$relation_query->getQuery()->bindings['where']);

        $query->addSelect($db->raw('`blends`.*'));
        $query->addSelect($db->raw("({$subQuery->toSql()}) as {$relation}_count"));//->mergeBindings($subQuery);
        // Since the bindings are now in the select part of the query, they need to be moved from where to select.
        // This prevents an error where the pagination count is incorrect (thereby aborting the entire operation) because it removes the sub queries from the select (it deletes the select), but not the bindings, resulting in the bindings being mis-matched.
        $query->getQuery()->bindings['select'] = array_merge($query->getQuery()->bindings['select'],$subQuery->bindings['where']);
        $query->groupBy('blends.id');
        return $query;
    }
}

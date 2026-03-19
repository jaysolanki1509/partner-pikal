<?php namespace App;

trait UserRelationShips {

    /**
     * role() one-to-one relationship method
     *
     * @return QueryBuilder
     */
    public function role()
    {
        return $this->belongsTo('App\DB\Role');
    }
}
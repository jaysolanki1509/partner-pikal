<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Closure;
use InvalidArgumentException;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
	}
// needed because of the TNT search scout package uses methods that are specific from laravel 5.6
// may be able to remove this if this app gets upgraded OR they fix the issue: https://github.com/teamtnt/laravel-scout-tntsearch-driver/issues/171
QueryBuilder::macro('joinSub', function ($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false) {
    list($query, $bindings) = $this->createSub($query);
    $expression = '('.$query.') as '.$this->grammar->wrap($as);
    $this->addBinding($bindings, 'join');
    return $this->join(new Expression($expression), $first, $operator, $second, $type, $where);
});

QueryBuilder::macro('leftJoinSub', function ($query, $as, $first, $operator = null, $second = null) {
    return $this->joinSub($query, $as, $first, $operator, $second, 'left');
});

QueryBuilder::macro('createSub', function ($query) {
    if ($query instanceof Closure) {
        $callback = $query;
        $callback($query = $this->forSubQuery());
    }
    return $this->parseSub($query);
});

QueryBuilder::macro('parseSub', function ($query) {
    if ($query instanceof self || $query instanceof EloquentBuilder) {
        return [$query->toSql(), $query->getBindings()];
    } elseif (is_string($query)) {
        return [$query, []];
    } else {
        throw new InvalidArgumentException;
    }
});

}

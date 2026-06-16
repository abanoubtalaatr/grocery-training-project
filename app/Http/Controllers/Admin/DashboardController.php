<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Set dynamic locale from the 'admin-lang' cookie, default to Arabic 'ar'
        $locale = $_COOKIE['admin-lang'] ?? 'ar';
        if (in_array($locale, ['ar', 'en'])) {
            app()->setLocale($locale);
        }
    }


    protected function getModels(): array
    {
        $modelsPath = app_path('Models');
        if (!File::isDirectory($modelsPath)) {
            return [];
        }

        $files = File::files($modelsPath);
        $models = [];

        foreach ($files as $file) {
            $modelName = $file->getBasename('.php');
            $className = 'App\\Models\\' . $modelName;

            if (class_exists($className)) {
                try {
                    $reflection = new \ReflectionClass($className);
                    if ($reflection->isSubclassOf(Model::class) && !$reflection->isAbstract()) {
                        $instance = new $className();
                        $models[] = [
                            'name' => $modelName,
                            'class' => $className,
                            'table' => $instance->getTable(),
                            'count' => $className::count(),
                            'primary_key' => $instance->getKeyName(),
                        ];
                    }
                } catch (\Exception $e) {
                }
            }
        }

        usort($models, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $models;
    }

    public function index()
    {
        $models = $this->getModels();

        $stats = [
            'users_count' => \App\Models\User::count(),
            'active_users_count' => \App\Models\User::where('is_active', true)->count(),
            'meals_count' => \App\Models\Meal::count(),
            'orders_count' => \App\Models\Order::count(),
            'pending_orders_count' => \App\Models\Order::where('status', 'placed')->count(), 
            'reviews_count' => \App\Models\Review::count(),
            'total_revenue' => \App\Models\Order::where('status', '!=', 'cancelled')->sum('total'),
        ];

        return view('admin.dashboard', compact('models', 'stats'));
    }

    public function modelList(string $model)
    {
        $models = $this->getModels();
        $modelClass = 'App\\Models\\' . ucfirst($model);

        if (!class_exists($modelClass)) {
            abort(404, "Model {$model} not found.");
        }

        $instance = new $modelClass();
        $table = $instance->getTable();
        $columns = Schema::getColumnListing($table);
        $primaryKey = $instance->getKeyName();

        $relations = [];
        foreach ($columns as $column) {
            if (Str::endsWith($column, '_id')) {
                $relationName = Str::camel(substr($column, 0, -3));
                if (method_exists($instance, $relationName)) {
                    $relations[] = $relationName;
                }
            }
        }

        $query = $modelClass::with($relations);

        if ($search = request('search')) {
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $column) {
                    if (in_array($column, ['password', 'remember_token', 'stripe_checkout_session_id', 'stripe_payment_intent_id'])) {
                        continue;
                    }
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        $sort = request('sort', $primaryKey);
        $direction = request('direction', 'desc');

        if (in_array($sort, $columns)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy($primaryKey, 'desc');
        }

        $records = $query->paginate(15)->withQueryString();

        return view('admin.models.index', compact(
            'models',
            'model',
            'columns',
            'records',
            'primaryKey',
            'sort',
            'direction'
        ));
    }

    public function modelDetail(string $model, $id)
    {
        $models = $this->getModels();
        $modelClass = 'App\\Models\\' . ucfirst($model);

        if (!class_exists($modelClass)) {
            abort(404, "Model {$model} not found.");
        }

        $instance = new $modelClass();
        $columns = Schema::getColumnListing($instance->getTable());

        $relations = [];
        foreach ($columns as $column) {
            if (Str::endsWith($column, '_id')) {
                $relationName = Str::camel(substr($column, 0, -3));
                if (method_exists($instance, $relationName)) {
                    $relations[] = $relationName;
                }
            }
        }

        $record = $modelClass::with($relations)->findOrFail($id);

        return view('admin.models.show', compact('models', 'model', 'record', 'columns'));
    }
}

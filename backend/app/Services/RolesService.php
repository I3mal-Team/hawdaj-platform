<?php
/**
 * Created by PhpStorm.
 * User: hassan
 * Date: 12/7/19
 * Time: 5:26 PM
 */
namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RolesService
{
    /**
     *  Define basic operations to be used for each model permissions.
     */
    public const BASIC_ROLES = ['root', 'admin', 'customer'];

    /**
     *  Define basic operations to be used for each model permissions.
     */
    public const BASIC_OPERATIONS = ['create', 'read', 'update', 'delete'];

    /**
     *  Define custom models.
     */
    public const ADDITIONAL_MODELS = ['dashboard','Report'];

    /**
     *  Define additional operations to be used for specific model permissions.
     */
    public const ADDITIONAL_MODEL_OPERATIONS = [
        [
            'name' => 'dashboard',
            'operations' => ['read']
        ],
        [
            'name' => 'report',
            'operations' => ['read','update']
        ]
    ];

    public static function GetModels(...$exceptions): Collection
    {
        return collect(scandir(app_path('Models')))->filter(function ($file_or_directory) {

            return Str::contains($file_or_directory, 'php');

        })->map(function ($file) {

            return str_replace('.php', '', $file);

        })->filter(function ($class) {
            $className = 'App\\Models\\' . $class;

            $objet = new $className();

            if (is_object($objet) && $objet->inPermission != false) {
                return $class;
            }

        })->merge(self::ADDITIONAL_MODELS)->filter(function ($model) use ($exceptions) {

            return !in_array($model, $exceptions, true);
        });
    }

    /**
     * Create any role with basic permissions on specific models.
     * @param $role
     * @param Collection $models
     * @return Role
     */

    public static function CreateRole($role = [null], Collection $models = null)
    {
        collect(array_filter(array_merge(self::BASIC_ROLES, $role)))->each(function ($item) use ($models) {

            $label = ucwords(str_replace(['-', '_'], ' ', $item));

            $roleModel = Role::firstOrCreate(['name' => $item, 'label' => $label]);

            $models = is_null($models) ? self::GetModels() : $models;

            self::AssignModelPermissionsToRole($roleModel, $models);
        });
    }

    /**
     * Create basic permissions for passed model.
     *
     * @param $model_name
     * @return Collection
     */
    public static function CreateModelPermissions(string $model_name): Collection
    {
        $permissions = collect();

        $operations = self::PrepareOperations($model_name);

        foreach ($operations as $operation) {
            $permissions->push(self::FindOrCreatePermission($model_name, $operation));
        }

        return $permissions;
    }

    /**
     * @param string $model
     * @param $operation
     * @return Permission
     */
    private static function FindOrCreatePermission(string $model, string $operation): Permission
    {
        $model_name = Str::snake($model);

        return Permission::firstOrCreate(
            [
                'name' => "{$operation}-{$model_name}",
                'guard_name' => 'web'
            ],
            [
                'name' => "{$operation}-{$model_name}",
                'label' => ucfirst($operation) . ' ' . $model,
                'model' => $model,
                'guard_name' => 'web'
            ]
        );
    }

    /**
     * Creating models' basic permissions (CRUD) and assign them to the role.
     *
     * @param Role $role
     * @param Collection $models
     */
    private static function AssignModelPermissionsToRole(Role $role, Collection $models): void
    {
        $models->each(function ($model) use ($role) {

            // At first we have to create all model permissions.
            $permissions = self::CreateModelPermissions($model);

            // then we assign all of this permissions to super-web role.
            $role->givePermissionTo($permissions);

        });
    }

    /**
     * @param string $model_name
     * @return array
     */
    private static function PrepareOperations(string $model_name): array
    {
        $additional_operations = collect(self::ADDITIONAL_MODEL_OPERATIONS);

        $operations = self::BASIC_OPERATIONS;

        if ($additional_operations->contains('name', $model_name)) {
            $model_additional_operations = $additional_operations->where('name', $model_name)->first();
            $operations = isset($model_additional_operations['basic']) && $model_additional_operations['basic']
                ? array_unique(array_merge($operations, $model_additional_operations['operations']))
                : $model_additional_operations['operations'];
        }

        return $operations;
    }
}

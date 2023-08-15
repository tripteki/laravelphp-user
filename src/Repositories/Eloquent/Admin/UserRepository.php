<?php

namespace Tripteki\User\Repositories\Eloquent\Admin;

use Error;
use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\User\Contracts\Repository\Admin\IUserRepository;
use Tripteki\RequestResponseQuery\QueryBuilder;

class UserRepository implements IUserRepository
{
    /**
     * @param array $querystring|[]
     * @return mixed
     */
    public function all($querystring = [])
    {
        $querystringed =
        [
            "limit" => $querystring["limit"] ?? request()->query("limit", 10),
            "current_page" => $querystring["current_page"] ?? request()->query("current_page", 1),
        ];
        extract($querystringed);

        $model = app(AuthModelContract::class);
        $field = keyName($model);
        $fields = ! empty($model->getVisible()) ? $model->getVisible() : array_values(array_diff_assoc(Schema::getColumnListing($model->getTable()), $model->getHidden()));

        $content = QueryBuilder::for($model->withoutRelations()->query())->
        defaultSort($field)->
        allowedSorts($fields)->
        allowedFilters($fields)->
        paginate($limit, [ "*", ], "current_page", $current_page)->appends(empty($querystring) ? request()->query() : $querystringed);

        return $content;
    }

    /**
     * @param int|string $identifier
     * @param array $querystring|[]
     * @return mixed
     */
    public function get($identifier, $querystring = [])
    {
        $content = app(AuthModelContract::class)->findOrFail($identifier)->withoutRelations();

        return $content;
    }

    /**
     * @param int|string $identifier
     * @param array $data
     * @return mixed
     */
    public function update($identifier, $data)
    {
        $content = app(AuthModelContract::class)->findOrFail($identifier)->withoutRelations();

        DB::beginTransaction();

        try {

            if (isset($data["password"])) $data["password"] = Hash::make($data["password"]);

            $content->update($data);

            DB::commit();

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        $content = null;

        DB::beginTransaction();

        try {

            if (isset($data["password"])) $data["password"] = Hash::make($data["password"]);

            $content = app(AuthModelContract::class)->create($data)->withoutRelations();

            if ($content instanceof MustVerifyEmail) {

                $content->markEmailAsVerified();
            }

            DB::commit();

            event(new Verified($content));

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }

    /**
     * @param int|string $identifier
     * @return mixed
     */
    public function delete($identifier)
    {
        $content = app(AuthModelContract::class)->findOrFail($identifier)->withoutRelations();

        DB::beginTransaction();

        try {

            $content->delete();

            DB::commit();

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }
};

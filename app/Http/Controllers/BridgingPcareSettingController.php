<?php

namespace App\Http\Controllers;

use App\Models\BridgingPcareSetting;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BridgingPcareSettingController extends Controller
{
    use Track;
    protected $pcare;

    public function __construct(Type $var = null)
    {
        $this->pcare = new BridgingPcareSetting();
    }
    function index()
    {
        $pcareSetting = BridgingPcareSetting::first();
        return view('content.setting', ['data' => $pcareSetting]);
    }
    function create(Request $request)
    {

        $data = $request->validate([
            'user' => 'required',
            'password' => 'required',
            'userIcare' => 'required',
            'passwordIcare' => 'required'
        ]);

        $this->truncate();

        try {
            $post = $this->pcare->create($data);
            if ($post) {
                $this->insertSql($this->pcare, $data);
                $createdRecord = $this->pcare->find($post->id);
            }
        } catch (QueryException $e) {
            return $this->error($e->errorInfo, 'failed', 500);
        }
        return $this->success(['created_at' => $createdRecord->created_at], 'success', 200);
    }

    function get(): JsonResponse
    {
        $pcare = $this->pcare->select(['created_at', 'user', 'password', 'userIcare', 'passwordIcare'])->first();
        if (!$pcare) {
            return $this->error(null, 'empty', 404);
        }
        return $this->success($pcare, 'success', 200);
    }
    function getUser()
    {
        $pcareSetting = BridgingPcareSetting::select('user')->first();
        return response()->json($pcareSetting->user);
    }

    function truncate()
    {
        $pcareSetting = $this->pcare->first();
        if ($pcareSetting) {
            $delete = $this->pcare->truncate();
            if ($delete) {
                $this->deleteSql($this->pcare, ['id' => $pcareSetting->id]);
            }
        }
    }
}

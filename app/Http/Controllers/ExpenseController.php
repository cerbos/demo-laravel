<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Cerbos\Sdk\Builder\AttributeValue;
use Cerbos\Sdk\Builder\CheckResourcesRequest;
use Cerbos\Sdk\Builder\PlanResourcesRequest;
use Cerbos\Sdk\Builder\Principal;
use Cerbos\Sdk\Builder\Resource;
use Cerbos\Sdk\Builder\ResourceEntry;
use Cerbos\Sdk\CerbosClient;
use Cerbos\Sdk\Utility\RequestId;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 *
 */
class ExpenseController extends Controller
{
    /**
     * @param Request $request
     * @param CerbosClient $cerbos
     * @return \Illuminate\Foundation\Application|Application|Response|ResponseFactory
     * @throws Exception
     */
    public function list(Request $request, CerbosClient $cerbos): Application|ResponseFactory|\Illuminate\Foundation\Application|Response
    {
        $action = "view";
        $resourceId = RequestId::generate();
        $plan = $cerbos->planResources(
            PlanResourcesRequest::newInstance()
                ->withRequestId(RequestId::generate())
                ->withPrincipal(
                    Principal::newInstance($request->user()->id)
                        ->withRoles(explode(',', $request->user()->roles))
                        ->withAttribute('region', AttributeValue::stringValue($request->user()->region))
                        ->withAttribute('department', AttributeValue::stringValue($request->user()->department))
                )
                ->withResource(
                    Resource::newInstance("expense", $resourceId)
                )
                ->withAction($action)
        );

        if ($plan->isAlwaysDenied()) {
            return response([], 200);
        }
        else if ($plan->isAlwaysAllowed()) {
            return Expense::where('owner_id', $request->user()->ownerId);
        }

        // TODO use filters
        return response([], 401);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param CerbosClient $cerbos
     * @return Application|\Illuminate\Foundation\Application|JsonResponse|Response|ResponseFactory
     * @throws Exception
     */
    public function get(Request $request, int $id, CerbosClient $cerbos): \Illuminate\Foundation\Application|Response|JsonResponse|Application|ResponseFactory
    {
        $expense = Expense::where('id', $id)->first();
        if (!$expense) {
            return response()->json(["error" => "failed to find related expense"], 404);
        }

        $viewAction = "view";
        $viewApproverAction = "view:approver";
        $approveAction = "approve";
        $deleteAction = "delete";
        $updateAction = "update";

        $response = $cerbos->checkResources(
            CheckResourcesRequest::newInstance()
                ->withRequestId(RequestId::generate())
                ->withPrincipal(
                    Principal::newInstance($request->user()->id)
                        ->withRoles(explode(',', $request->user()->roles))
                        ->withAttribute('region', AttributeValue::stringValue($request->user()->region))
                        ->withAttribute('department', AttributeValue::stringValue($request->user()->department))
                )
                ->withResourceEntry(
                    ResourceEntry::newInstance("expense", $expense->id)
                        ->withAttribute("amount", AttributeValue::floatValue($expense->amount))
                        ->withAttribute("region", AttributeValue::stringValue($expense->region))
                        ->withAttribute("status", AttributeValue::stringValue($expense->status))
                        ->withAttribute("ownerId", AttributeValue::stringValue($expense->owner_id))
                        ->withAttribute("vendor", AttributeValue::stringValue($expense->vendor))
                        ->withActions(array($viewAction, $viewApproverAction, $approveAction, $deleteAction, $updateAction))
                )
        )->find($expense->id);

        $permissions = [
            "canApprove" => $response->isAllowed($approveAction),
            "canDelete" => $response->isAllowed($deleteAction),
            "canUpdate" => $response->isAllowed($updateAction),
            "canView" => $response->isAllowed($viewAction),
            "canViewApprover" => $response->isAllowed($viewApproverAction)
        ];

        if (!$response->isAllowed($viewAction)) {
            return response([], 401);
        }
        else if (!$response->isAllowed($viewApproverAction)) {
            $expense->approvedBy = null;
        }

        return response()->json([
            "expense" => $expense,
            "permissions" => $permissions
        ], 200);
    }

    /**
     * @param Expense $expense
     * @param CerbosClient $cerbos
     * @return void
     */
    public function create(Request $request, Expense $expense, CerbosClient $cerbos) {
        // TODO
    }

    /**
     * @param Request $request
     * @param int $id
     * @param CerbosClient $cerbos
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Request $request, int $id, CerbosClient $cerbos): JsonResponse
    {
        $expense = Expense::where('id', $id)->first();
        if (!$expense) {
            return response()->json(["error" => "failed to find related expense"], 404);
        }

        $action = "delete";
        $allowed = $cerbos->checkResources(
            CheckResourcesRequest::newInstance()
                ->withRequestId(RequestId::generate())
                ->withPrincipal(
                    Principal::newInstance($request->user()->id)
                        ->withRoles(explode(',', $request->user()->roles))
                        ->withAttribute('region', AttributeValue::stringValue($request->user()->region))
                        ->withAttribute('department', AttributeValue::stringValue($request->user()->department))
                )
                ->withResourceEntry(
                    ResourceEntry::newInstance("expense", $expense->id)
                        ->withAttribute("amount", AttributeValue::floatValue($expense->amount))
                        ->withAttribute("region", AttributeValue::stringValue($expense->region))
                        ->withAttribute("status", AttributeValue::stringValue($expense->status))
                        ->withAttribute("ownerId", AttributeValue::stringValue($expense->owner_id))
                        ->withAttribute("vendor", AttributeValue::stringValue($expense->vendor))
                        ->withAction($action)
                )
        )->find($expense->id)->isAllowed($action);

        if (!$allowed) {
            return response(null, 401);
        }

        Expense::where('id', $id).delete();
        return $expense;
    }

    /**
     * @param Request $request
     * @param int $id
     * @param Expense $expense
     * @param CerbosClient $cerbos
     * @return void
     */
    public function update(Request $request, int $id, Expense $expense, CerbosClient $cerbos) {
        // TODO
    }

    /**
     * @param Request $request
     * @param int $id
     * @param CerbosClient $cerbos
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|JsonResponse|Response
     * @throws Exception
     */
    public function approve(Request $request, int $id, CerbosClient $cerbos): \Illuminate\Foundation\Application|Response|JsonResponse|Application|ResponseFactory
    {
        $expense = Expense::where('id', $id)->first();
        if (!$expense) {
            return response()->json(["error" => "failed to find related expense"], 404);
        }

        $action = "approve";
        $allowed = $cerbos->checkResources(
            CheckResourcesRequest::newInstance()
                ->withRequestId(RequestId::generate())
                ->withPrincipal(
                    Principal::newInstance($request->user()->id)
                        ->withRoles(explode(',', $request->user()->roles))
                        ->withAttribute('region', AttributeValue::stringValue($request->user()->region))
                        ->withAttribute('department', AttributeValue::stringValue($request->user()->department))
                )
                ->withResourceEntry(
                    ResourceEntry::newInstance("expense", $expense->id)
                        ->withAttribute("amount", AttributeValue::floatValue($expense->amount))
                        ->withAttribute("region", AttributeValue::stringValue($expense->region))
                        ->withAttribute("status", AttributeValue::stringValue($expense->status))
                        ->withAttribute("ownerId", AttributeValue::stringValue($expense->owner_id))
                        ->withAttribute("vendor", AttributeValue::stringValue($expense->vendor))
                        ->withAction($action)
                )
        )->find($expense->id)->isAllowed($action);

        if (!$allowed) {
            return response(null, 401);
        }

        return Expense::where('id', $id)->update([
            'status' => 'APPROVED',
            'approved_by' => $request->user()->$id
        ])->first();
    }

    /**
     * @param Request $request
     * @param int $id
     * @param CerbosClient $cerbos
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|JsonResponse|Response
     * @throws Exception
     */
    public function reject(Request $request, int $id, CerbosClient $cerbos): \Illuminate\Foundation\Application|Response|JsonResponse|Application|ResponseFactory
    {
        $expense = Expense::where('id', $id)->first();
        if (!$expense) {
            return response()->json(["error" => "failed to find related expense"], 404);
        }

        $action = "approve";
        $allowed = $cerbos->checkResources(
            CheckResourcesRequest::newInstance()
                ->withRequestId(RequestId::generate())
                ->withPrincipal(
                    Principal::newInstance($request->user()->id)
                        ->withRoles(explode(',', $request->user()->roles))
                        ->withAttribute('region', AttributeValue::stringValue($request->user()->region))
                        ->withAttribute('department', AttributeValue::stringValue($request->user()->department))
                )
                ->withResourceEntry(
                    ResourceEntry::newInstance("expense", $expense->id)
                        ->withAttribute("amount", AttributeValue::floatValue($expense->amount))
                        ->withAttribute("region", AttributeValue::stringValue($expense->region))
                        ->withAttribute("status", AttributeValue::stringValue($expense->status))
                        ->withAttribute("ownerId", AttributeValue::stringValue($expense->owner_id))
                        ->withAttribute("vendor", AttributeValue::stringValue($expense->vendor))
                        ->withAction($action)
                )
        )->find($expense->id)->isAllowed($action);

        if (!$allowed) {
            return response(null, 401);
        }

        return Expense::where('id', $id)->update([
            'status' => 'REJECTED',
            'approved_by' => $request->user()->$id
        ])->first();
    }
}
